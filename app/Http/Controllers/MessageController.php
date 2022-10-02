<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreMessageRequest;
use App\Services\Neo4jClient;

class MessageController extends Controller
{
    /**
     * Neo4j Client
     */
    protected $neo4j;

    /**
     * Create a new controller instance
     * 
     * @param  \App\Services\Neo4jClient
     * @return void
     */
    public function __construct(Neo4jClient $neo4j)
    {
        $this->neo4j = $neo4j->connect();
    }

    /**
     * Store new massage
     * 
     * @param  \App\Http\Requests\Message\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        try {
            $query = $this->neo4j->run(<<<'CYPHER'
            MATCH (author:People {id: $from_person_id})
            CREATE (message:Messages {
                text: $text,
                topics: $topics,
                from_person_id: $from_person_id,
                min_trust_level: $min_trust_level
            })
            CREATE (author)-[:CAN_VIEW]->(message)
            WITH message
            MATCH path = (author:People {id: $from_person_id}) - [:TRUSTS*] -> (receiver:People)
            WHERE
                receiver.id <> $from_person_id AND
                all(trust IN relationships(path) WHERE trust.level >= $min_trust_level) AND
                all(node IN nodes(path) WHERE all(topic IN $topics WHERE topic IN node.topics))
            WITH distinct receiver, message
            CREATE (receiver)-[:CAN_VIEW]->(message)
            return receiver.id as receiver_id
            CYPHER, [
                'text' => $request->safe()->text,
                'topics' => $request->safe()->topics,
                'from_person_id' => $request->safe()->from_person_id,
                'min_trust_level' => $request->safe()->min_trust_level
            ]);

            $results = [];
            foreach ($query as $item) {
                $results[] = $item->get('receiver_id');
            }

            return !empty($results) 
                ? response()->json([$request->safe()->from_person_id => $results], 201) 
                : response('Not found', 404);
        } catch (\Exception $e) {
            abort(422, $e->getMessage());
        }
    }
}
