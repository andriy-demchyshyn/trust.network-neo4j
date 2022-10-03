# Trust Network

## Stack
- Neoj4 Graph Database
- PHP 8.1
- Laravel 9

## How to start service
1. Start Docker Container: <code>docker-compose up -d</code>
2. Enter SSH: <code>docker exec -it trust.network.app bash</code>
3. Run tests

## API Endpoints
- <code>POST: /api/people</code>\
  <code>app\Http\Controllers\PeopleController::class, method: store</code>
- <code>POST: /api/people/{person_id}/trust_connections</code>\
  <code>app\Http\Controllers\TrustConnectionController::class, method: store</code>
- <code>POST: /api/messages</code>\
  <code>app\Http\Controllers\MessageController::class, method: store</code>
- <code>POST: /api/path</code>\
  <code>app\Http\Controllers\PathController::class, method: findShortestPath</code>