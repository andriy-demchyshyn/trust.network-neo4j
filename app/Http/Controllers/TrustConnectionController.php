<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrustConnection\StoreTrustConnectionsRequest;
use App\Services\Neo4jClient;

class TrustConnectionController extends Controller
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
     * Store trust connections for specified person
     * 
     * @param  \App\Http\Requests\TrustConnection\StoreTrustConnectionsRequest  $request
     * @param  string  $person_id
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTrustConnectionsRequest $request, string $person_id)
    {
        if (empty($request->all())) {
            abort(404, 'Empty request');
        }

        try {
            $query = $this->neo4j->run(<<<'CYPHER'
            MATCH (me:People {id: $person_id})
            MATCH (friend:People)
            WHERE friend.id IN keys($data)
            MERGE (me)-[trust:TRUSTS]->(friend)
            ON CREATE SET
                trust.level = coalesce($data[friend.id], 0)
            ON MATCH SET
                trust.level = coalesce($data[friend.id], 0)
            CYPHER, ['person_id' => $person_id, 'data' => $request->all()]);
        } catch (\Exception $e) {
            abort(422, 'Unprocessable request data');
        }

        $affected_connections = $query->getSummary()->getCounters()->propertiesSet();

        return $affected_connections > 0 
            ? response()->json($affected_connections, 201) 
            : abort(404, 'Person not found');
    }
}
