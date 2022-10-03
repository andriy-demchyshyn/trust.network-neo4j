# Trust Network

For this project I've decided to use Neo4j graph database, as it fits perfect for data structures described in task.

Next steps:
- add authentication
- possibility to fully manage own profile, topics, trust connections and messages
- possibility to reply to messages, probably something like comments, likes
- recommendations system
- private messaging
Over time, the functionality can be extended more and more

## Stack
- Neo4j Graph Database
- PHP 8.1
- Laravel 9

## How to start service
1. Start Docker container: <code>docker-compose up -d</code>
2. SSH connect to Docker container: <code>docker exec -it trust.network.app bash</code>
3. Install composer dependencies. Run in terminal: <code>composer install</code>
4. Run tests in terminal: <code>php artisan test</code>

## API Endpoints
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/people</code>\
  PHP Class: <code>app\Http\Controllers\PeopleController::class</code>\
  Method: <code>store</code>
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/people/{person_id}/trust_connections</code>\
  PHP Class: <code>app\Http\Controllers\TrustConnectionController::class</code>\
  Method: <code>store</code>
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/messages</code>\
  PHP Class: <code>app\Http\Controllers\MessageController::class</code>\
  Method: <code>store</code>
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/path</code>\
  PHP Class: <code>app\Http\Controllers\PathController::class</code>\
  Method: <code>findShortestPath</code>