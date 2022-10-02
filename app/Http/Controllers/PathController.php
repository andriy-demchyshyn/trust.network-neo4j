<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreMessageRequest;
use App\Services\Neo4jClient;

class PathController extends Controller
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
     * Store message with shortest path
     * 
     * @param  \App\Http\Requests\Message\ShowMessagesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMessageRequest $request)
    {
        $query = $this->neo4j->run(<<<'CYPHER'
        MATCH path = shortestPath((author:People {id: $from_person_id}) - [:TRUSTS*] -> (receiver:People))
        WHERE
            receiver.id <> $from_person_id AND
            all(trust IN relationships(path) WHERE trust.level >= $min_trust_level) AND
            all(topic IN $topics WHERE topic IN receiver.topics)
        WITH distinct receiver, path
        return receiver.id as receiver_id, nodes(path) as nodes, length(path) as path_length
        CYPHER, [
            'text' => $request->safe()->text,
            'topics' => $request->safe()->topics,
            'from_person_id' => $request->safe()->from_person_id,
            'min_trust_level' => $request->safe()->min_trust_level,
            'created_at' => time()
        ]);

        $shortest_path = [];
        $min_length = 0;
        foreach ($query->getResults() as $item) {
            $path_length = $item->get('path_length');
            if (!empty($min_length) && $path_length >= $min_length) {
                continue;
            }

            $min_length = $path_length;
            $path_nodes = $item->get('nodes');
            $path_persons = [];
            foreach ($path_nodes as $path_node) {
                if ($path_node->getProperty('id') === $request->safe()->from_person_id) {
                    continue;
                }
                $path_persons[] = $path_node->getProperty('id');
            }
            $shortest_path = [
                'from' => $request->safe()->from_person_id,
                'path' => $path_persons,
            ];
        }

        return response()->json($shortest_path, 201);
    }
}
