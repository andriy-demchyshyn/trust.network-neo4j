# Trust Network

## Stack
- Neoj4 Graph Database
- PHP 8.1
- Laravel 9

## How to start service
1. Start Docker Container: <code>docker-compose up -d</code>
2. Enter terminal: <code>docker exec -it trust.network.app bash</code>
3. Run tests in terminal: <code>php artisan test</code>

## API Endpoints
- POST: <code>http\://127.0.0.1:8080/api/people</code>\
  <code>app\Http\Controllers\PeopleController::class, method: store</code>
- POST: <code>http\://127.0.0.1:8080/api/people/{person_id}/trust_connections</code>\
  <code>app\Http\Controllers\TrustConnectionController::class, method: store</code>
- POST: <code>http\://127.0.0.1:8080/api/messages</code>\
  <code>app\Http\Controllers\MessageController::class, method: store</code>
- POST: <code>http\://127.0.0.1:8080/api/path</code>\
  <code>app\Http\Controllers\PathController::class, method: findShortestPath</code>