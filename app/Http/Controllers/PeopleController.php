<?php

namespace App\Http\Controllers;

use App\Http\Requests\People\StorePersonRequest;
use App\Services\Neo4jClient;

class PeopleController extends Controller
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
     * Store new person
     * 
     * @param  \App\Http\Requests\People\StorePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonRequest $request)
    {
        $query = $this->neo4j->run(<<<'CYPHER'
        MERGE (person:People {id: $id})
        ON CREATE SET
            person.topics = $topics
        ON MATCH SET
            person.topics = person.topics + [topic IN $topics WHERE NOT topic IN person.topics]
        RETURN person
        CYPHER, ['id' => $request->safe()->id, 'topics' => $request->safe()->topics]);

        return response()->json($query->first()->get('person')->getProperties(), 201);
    }
}
