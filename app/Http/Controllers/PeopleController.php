<?php

namespace App\Http\Controllers;

use App\Http\Requests\People\ShowPeopleRequest;
use App\Http\Requests\People\ShowPersonRequest;
use App\Http\Requests\People\StorePersonRequest;
use App\Http\Requests\People\UpdatePersonRequest;
use App\Http\Requests\People\DestroyPersonRequest;
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
     * Show people
     * 
     * @param  \App\Http\Requests\People\ShowPeopleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function index(ShowPeopleRequest $request)
    {
        abort(403); // Method is not used yet, should be implemented in future
    }

    /**
     * Show person
     * 
     * @param  \App\Http\Requests\People\ShowPersonRequest  $request
     * @param  string  $person_id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowPersonRequest $request, string $person_id)
    {
        $query = $this->neo4j->run(<<<'CYPHER'
        MATCH (person:People {id: $id}) RETURN person
        CYPHER, ['id' => $person_id]);

        return response()->json($query->first()->get('person')->getProperties(), 200);
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

    /**
     * Update person
     * 
     * @param  \App\Http\Requests\People\UpdatePersonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePersonRequest $request, $id)
    {
        abort(403); // Method is not used yet, should be implemented in future
    }

    /**
     * Remove person
     * 
     * @param  \App\Http\Requests\People\DestroyPersonRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DestroyPersonRequest $request, $id)
    {
        abort(403); // Method is not used yet, should be implemented in future
    }
}
