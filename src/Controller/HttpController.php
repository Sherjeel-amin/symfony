<!-- Installation

First, make sure the HTTP Client component is installed in your Symfony project:

bash

composer require symfony/http-client

Basic Usage
Making a GET Request

 

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();
$response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1');

$statusCode = $response->getStatusCode();
$content = $response->getContent();
$data = $response->toArray();

echo $statusCode; // 200
print_r($data); // Array of response data

Making a POST Request

 

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();
$response = $client->request('POST', 'https://jsonplaceholder.typicode.com/posts', [
    'json' => [
        'title' => 'foo',
        'body' => 'bar',
        'userId' => 1,
    ],
]);

$statusCode = $response->getStatusCode();
$content = $response->getContent();
$data = $response->toArray();

echo $statusCode; // 201
print_r($data); // Array of response data

Handling Responses
Checking Status Code

 

$statusCode = $response->getStatusCode();
if ($statusCode === 200) {
    echo 'Success!';
} else {
    echo 'Something went wrong.';
}

Getting Response Headers

 

$headers = $response->getHeaders();
print_r($headers);

Getting the Response Content

 

$content = $response->getContent();
echo $content;

Converting Response to Array

 

$data = $response->toArray();
print_r($data);

Handling Errors
Try-Catch Block

 

use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

try {
    $response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1');
    $data = $response->toArray();
    print_r($data);
} catch (HttpExceptionInterface $e) {
    echo $e->getMessage();
} catch (TransportExceptionInterface $e) {
    echo $e->getMessage();
}

Advanced Usage
Adding Headers

 

$response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1', [
    'headers' => [
        'Accept' => 'application/json',
        'Authorization' => 'Bearer your_token_here',
    ],
]);

Setting Query Parameters

 

$response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts', [
    'query' => [
        'userId' => 1,
    ],
]);

Sending JSON Data

 

$response = $client->request('POST', 'https://jsonplaceholder.typicode.com/posts', [
    'json' => [
        'title' => 'foo',
        'body' => 'bar',
        'userId' => 1,
    ],
]);

Sending Form Data

 

$response = $client->request('POST', 'https://example.com/form', [
    'body' => [
        'field1' => 'value1',
        'field2' => 'value2',
    ],
]);

Uploading Files

 

$response = $client->request('POST', 'https://example.com/upload', [
    'headers' => [
        'Content-Type' => 'multipart/form-data',
    ],
    'body' => [
        'file' => fopen('/path/to/file', 'r'),
    ],
]);

Concurrent Requests

 

use Symfony\Component\HttpClient\HttpClient;

$client = HttpClient::create();

$requests = [
    'request1' => $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1'),
    'request2' => $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/2'),
];

foreach ($client->stream($requests) as $response => $chunk) {
    if ($chunk->isLast()) {
        $data = $response->toArray();
        print_r($data);
    }
}

Configuration Options
Timeout

 

$response = $client->request('GET', 'https://jsonplaceholder.typicode.com/posts/1', [
    'timeout' => 10,
]);

Base URI



$client = HttpClient::create(['base_uri' => 'https://jsonplaceholder.typicode.com']);
$response = $client->request('GET', '/posts/1');

Verify SSL Certificates


$response = $client->request('GET', 'https://example.com', [
    'verify_peer' => false,
    'verify_host' => false,
]);

Debugging
Enabling Debug Mode



use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpClient\RetryableHttpClient;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Component\HttpClient\TraceableHttpClient;
use Symfony\Component\Stopwatch\Stopwatch;

$stopwatch = new Stopwatch();
$traceableClient = new TraceableHttpClient(HttpClient::create(), $stopwatch);
$response = $traceableClient->request('GET', 'https://jsonplaceholder.typicode.com/posts/1');

$events = $stopwatch->getSectionEvents('__root__');
print_r($events); -->
