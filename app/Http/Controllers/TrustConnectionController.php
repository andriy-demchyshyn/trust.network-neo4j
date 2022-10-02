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
        if (empty($request->all()) || !is_array($request->all())) {
            abort(422);
        }

        $request_validated = array_filter($request->all(), function($value, $key) {
            return !empty($key) && is_numeric($value) && $value >= 1 && $value <= 10;
        }, ARRAY_FILTER_USE_BOTH);

        $query = $this->neo4j->run(<<<'CYPHER'
        MATCH (me:People {id: $person_id})
        MATCH (friend:People)
        WHERE friend.id IN keys($data)
        MERGE (me)-[trust:TRUSTS]->(friend)
        ON CREATE SET
            trust.level = coalesce($data[friend.id], 0)
        ON MATCH SET
            trust.level = coalesce($data[friend.id], 0)
        CYPHER, ['person_id' => $person_id, 'data' => $request_validated]);

        return response()->json($query->getSummary()->getCounters()->propertiesSet(), 201);
    }
}
