# DEV Challenge Online Round '2022 - Trust Network

## Task
Trust network is a community of people who know each other and can assign each other a trust level. We usually use trust networks in real life when donating money, searching for healthcare professionals or expertise: we search between friends, friends of friends, etc.

Let's model information flow in our computer. The main entities are
1. Person, which has the next attributes:
  - id
  - set of expertise topics (strings)
  - set of relations to other peoples: pairs
    - id - contact id
    - trustLevel - number between 1 or 10

We can build a model of trust network using straightforward REST API:


<b>Add people:</b>
```
POST: http://127.0.0.1:8080/api/people

POST body:
{
    "id": "Garry",
    "topics": ["books", "magic", "movies"]
}

Response 201:
{
    "id": "Garry",
    "topics": ["books", "magic", "movies"]
}
```


<b>Update or create trust connections:</b>
```
POST: http://127.0.0.1:8080/api/{id}/trust_connections

POST body (hash pair with person_id - trust level):
{
    "Ron": 10,
    "Hermione": 10
}

Response 201
```


<b>The main work is a sending messages (question, search for expertise, etc), which should have form:</b>
```
POST: http://127.0.0.1:8080/api/messages

POST body (hash pair with person_id - trust level):
{
    "text": "Voldemort is alive!",
    "topics": ["magic"],
    "from_person_id": "Garry",
    "min_trust_level": 5
}

Response should trace message delivery through the network based on people topics and trust connection levels. 
Each person should receive this message only one time and not be spammed. 
All persons who receive a message must have appropriate topics. 
Note, that message is send broadcasted to all.

Response 201
{
    "Garry": ["Hermione", "Ron"]
}
```


<b>Bonus: implement delivery of non-broadcast message, where receiver should have topics listed in requests, intermediate nodes can not have topics, listed in request</b>
```
POST: http://127.0.0.1:8080/api/path

POST body (hash pair with person_id - trust level):
{
    "text": "Need to find an expertise in magic",
    "topics": ["books", "magic"],
    "from_person_id": "Garry",
    "min_trust_level": 5
}

This message should find an receiver, which have appropriate topics in attributes. 
All participants in the path should be connected with a trust-level of 5 or more.

Response 201
{
    "from": "Garry",
    "path": ["Hermione"]
}
Response is the path from the message sender to the message receiver, including all intermediate agents. 
When we have more than one variant, we should return a shorter variant.
```

## Solution
For this project I've used Neo4j graph database, as it fits perfect for data structures described in task.

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