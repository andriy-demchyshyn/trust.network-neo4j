<?php

namespace App\Services;

use Laudis\Neo4j\ClientBuilder;
use Laudis\Neo4j\Contracts\ClientInterface;

class Neo4jClient
{
    /**
     * Neo4j Client
     * 
     * @return \Laudis\Neo4j\Contracts\ClientInterface
     */
    public static function connect(): ClientInterface
    {
        $neo4j_login = config('database.connections.neo4j.login');
        $neo4j_password = config('database.connections.neo4j.password');
        $neo4j_host = config('database.connections.neo4j.host');

        return ClientBuilder::create()->withDriver(
            'bolt',
            'bolt://'.$neo4j_login.':'.$neo4j_password.'@'.$neo4j_host.''
        )->build();
    }
}
