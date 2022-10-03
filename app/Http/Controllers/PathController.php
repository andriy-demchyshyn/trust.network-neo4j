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
            WITH distinct receiver, path
            return receiver.id as receiver_id, nodes(path) as nodes, length(path) as path_length
            CYPHER, [
                'text' => $request->safe()->text,
                'topics' => $request->safe()->topics,
                'from_person_id' => $request->safe()->from_person_id,
                'min_trust_level' => $request->safe()->min_trust_level,
            ]);
        } catch (\Exception $e) {
            abort(422, 'Unprocessable request data');
        }

        $shortest_path = $this->selectShortestPath($request, $query);

        return !empty($shortest_path) 
            ? response()->json(['from' => $request->safe()->from_person_id, 'path' => $shortest_path], 201) 
            : abort(404, 'Message is not sent');
    }

    /**
     * Select shortest path
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $query
     * @return array
     */
    protected function selectShortestPath(Request $request, mixed $query): array
    {
        $shortest_path = [];
        foreach ($query->getResults() as $item) {
            $path_length = $item->get('path_length');
            if (!empty($shortest_path) && $path_length >= count($shortest_path)) {
                continue;
            }

            $shortest_path = [];
            $path_nodes = $item->get('nodes');
            foreach ($path_nodes as $path_node) {
                if ($path_node->getProperty('id') === $request->safe()->from_person_id) {
                    continue;
                }
                $shortest_path[] = $path_node->getProperty('id');
            }
        }

        return $shortest_path;
    }
}
