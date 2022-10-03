# Trust Network

## Stack
- Neo4j Graph Database
- PHP 8.1
- Laravel 9

## How to start service
1. Start Docker Container: <code>docker-compose up -d</code>
2. Enter terminal: <code>docker exec -it trust.network.app bash</code>
3. Run in terminal: <code>composer install</code>
4. Run tests in terminal: <code>php artisan test</code>

## API Endpoints
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/people</code>\
  <b>PHP Class:</b> <code>app\Http\Controllers\PeopleController::class</code> <b>Method:</b> <code>store</code>
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/people/{person_id}/trust_connections</code>\
  <b>PHP Class:</b> <code>app\Http\Controllers\TrustConnectionController::class, method: store</code>
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/messages</code>\
  <code>app\Http\Controllers\MessageController::class, method: store</code>
- <b>POST:</b> <code>http\://127.0.0.1:8080/api/path</code>\
  <code>app\Http\Controllers\PathController::class, method: findShortestPath</code>