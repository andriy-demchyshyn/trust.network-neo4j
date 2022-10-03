<?php

namespace App\Http\Controllers;

use App\Http\Requests\Message\StoreMessageRequest;
use App\Services\Neo4jClient;
use Illuminate\Http\Request;

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
     * Find shortest path
     * 
     * @param  \App\Http\Requests\Message\ShowMessagesRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function findShortestPath(StoreMessageRequest $request)
    {
        try {
            $query = $this->neo4j->run(<<<'CYPHER'
            MATCH path = shortestPath((author:People {id: $from_person_id}) - [:TRUSTS*] -> (receiver:People))
            WHERE
                receiver.id <> $from_person_id AND
                all(trust IN relationships(path) WHERE trust.level >= $min_trust_level) AND
                all(topic IN $topics WHERE topic IN receiver.topics)
            WITH distinct receiver, nodes(path) as nodes, length(path) as path_length
            ORDER BY path_length ASC
            LIMIT 1
            RETURN nodes
            CYPHER, [
                'text' => $request->safe()->text,
                'topics' => $request->safe()->topics,
                'from_person_id' => $request->safe()->from_person_id,
                'min_trust_level' => $request->safe()->min_trust_level,
            ]);
        } catch (\Exception $e) {
            abort(422, 'Unprocessable request data');
        }

        if (empty($query) || empty($query->getResults()->jsonSerialize())) {
            abort(404, 'Path not found');
        }

        $path = [];
        foreach ($query->first()->get('nodes') as $node) {
            if ($node->getProperty('id') != $request->safe()->from_person_id) {
                $path[] = $node->getProperty('id');
            }
        }

        return !empty($path) 
            ? response()->json(['from' => $request->safe()->from_person_id, 'path' => $path], 201) 
            : abort(404, 'Path not found');
    }
}
