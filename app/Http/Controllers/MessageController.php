<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\ShowMessagesRequest;
use App\Http\Requests\Message\ShowMessageRequest;
use App\Http\Requests\Message\StoreMessageRequest;
use App\Http\Requests\Message\UpdateMessageRequest;
use App\Http\Requests\Message\DestroyMessageRequest;
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
     * Show messages
     * 
     * @param  \App\Http\Requests\Message\ShowMessagesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ShowMessagesRequest $request)
    {
        abort(403); // Method is not used yet, should be implemented in future
    }

    /**
     * Show message
     * 
     * @param  \App\Http\Requests\Message\ShowMessageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowMessageRequest $request, $id)
    {
        abort(403); // Method is not used yet, should be implemented in future
    }

    /**
     * Store new massage
     * 
     * @param  \App\Http\Requests\Message\StoreMessageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        $query = $this->neo4j->run(<<<'CYPHER'
        MATCH (author:People {id: $from_person_id})
        CREATE (message:Messages {
            text: $text,
            topics: $topics,
            from_person_id: $from_person_id,
            min_trust_level: $min_trust_level,
            created_at: $created_at
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
            'min_trust_level' => $request->safe()->min_trust_level,
            'created_at' => time()
        ]);

        $results = [];
        foreach ($query as $item) {
            $results[] = $item->get('receiver_id');
        }

        return response()->json([
            $request->safe()->from_person_id => $results,
        ], 201);
    }

    /**
     * Update message
     * 
     * @param  \App\Http\Requests\Message\UpdateMessageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMessageRequest $request, $id)
    {
        abort(403); // Method is not used yet, should be implemented in future
    }

    /**
     * Remove message
     * 
     * @param  \App\Http\Requests\Message\DestroyMessageRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyMessageRequest $request, $id)
    {
        abort(403); // Method is not used yet, should be implemented in future
    }
}
