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

        // FOR TESTING PURPOSES ONLY
        // IN PRODUCTION CONSTRAINTS SHOULD BE CREATED MANUALLY, NOT PER EACH REQUEST
        $this->neo4j->run(<<<'CYPHER'
        CREATE CONSTRAINT unique_person_id IF NOT EXISTS
        FOR (person:People)
        REQUIRE person.id IS UNIQUE
        CYPHER);
    }

    /**
     * Store new person
     * 
     * @param  \App\Http\Requests\People\StorePersonRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePersonRequest $request)
    {
        try {
            $query = $this->neo4j->run(<<<'CYPHER'
            MERGE (person:People {id: $id})
            ON CREATE SET
                person.topics = $topics
            ON MATCH SET
                person.already_exists = 1
            WITH person, person.already_exists AS person_already_exists
            REMOVE person.already_exists
            RETURN person, person_already_exists
            CYPHER, ['id' => $request->safe()->id, 'topics' => $request->safe()->topics]);
        } catch (\Exception $e) {
            abort(422, 'Unprocessable request data');
        }

        return $query->first()->get('person_already_exists') == 0
            ? response()->json($query->first()->get('person')->getProperties(), 201)
            : abort(422, 'Person already exists');
    }
}
