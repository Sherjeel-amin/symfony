<?php
// src/Controller/DefaultController.php

// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// class DefaultController extends AbstractController
// {
//     #[Route(
//         '/contact',
//         name: 'contact',
//         condition: "context.getMethod() in ['GET', 'HEAD'] and request.headers.get('User-Agent') matches '/firefox/i'",
//         // expressions can also include config parameters:
//         // condition: "request.headers.get('User-Agent') matches '%app.allowed_browsers%'"
//     )]
//     public function contact(): Response
//     {
//         return new Response("");
//     }

//     #[Route(
//         '/posts/{id}',
//         name: 'post_show',
//         // expressions can retrieve route parameter values using the "params" variable
//         condition: "params['id'] < 1000"
//     )]
//     public function showPost(int $id): Response
//     {
//         return new Response("");
//     }
// }


// This defines a route for the /contact URL.

// #[Route('/contact', name: 'contact', ...)]: The Route attribute specifies the URL pattern (/contact) and the name of the route (contact).
//     condition: This option allows you to specify conditions that must be met for the route to match. In this case, the condition checks:
//         context.getMethod() in ['GET', 'HEAD']: The request method must be either GET or HEAD.
//         request.headers.get('User-Agent') matches '/firefox/i': The User-Agent header must contain the word "firefox" (case-insensitive).


// This defines a route for the /posts/{id} URL.

//     #[Route('/posts/{id}', name: 'post_show', ...)]: The Route attribute specifies the URL pattern (/posts/{id}) and the name of the route (post_show).
//     condition: This option specifies that the route will only match if the id parameter is less than 1000.

// The showPost method will handle the request if the id parameter is less than 1000.

// Context Methods
// context.getMethod(): Returns the HTTP method of the request (e.g., GET, POST).
// context.getHost(): Returns the host name from the request URL.
// context.getScheme(): Returns the scheme (http or https) of the request.
// context.getBaseUrl(): Returns the base URL of the request.


// request.headers: Access to request headers. For example, request.headers.get('User-Agent').
// request.query: Access to query parameters. For example, request.query.get('page').
// request.request: Access to POST parameters. For example, request.request.get('name').
// request.getMethod(): Returns the HTTP method of the request.
// request.getUri(): Returns the full URI for the request.



// params['id']: Accesses the id parameter from the URL.
// params['slug']: Accesses the slug parameter from the URL.