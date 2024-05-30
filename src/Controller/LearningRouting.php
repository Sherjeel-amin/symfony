<?php
// src/Controller/BlogApiController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// class BlogApiController extends AbstractController
// {
//     #[Route('/api/posts/{id}', methods: ['GET', 'HEAD'])]
//     public function show(int $id): Response
//     {
//         return new Response("Hello");
//     }

//     #[Route('/api/posts/{id}', methods: ['PUT'])]
//     public function edit(int $id): Response
//     {
//         return new Response("Hii");
//     }
// }

// By default, routes match any HTTP verb (GET, POST, PUT, etc.) Use the methods option to restrict the verbs each route should respond to:





//                          ======================== Parameter Validation ========================================================

// src/Controller/ArticleController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class ArticleController extends AbstractController
// {
//     #[Route('/articles/{page}', name: 'article_list', requirements: ['page' => '\d+'])]
//     public function list(int $page): Response
//     {
//         // This method will be executed when a user visits /articles/{page}
//         // where {page} is a positive integer.

//         // Your logic to fetch and display articles for the given page number goes here

//         return $this->render('article/list.html.twig', [
//             'page' => $page,
//         ]);
//     }
// }


// We define a route /articles/{page} where {page} is a dynamic parameter representing the page number.
// The requirements option is used to specify that the page parameter must match the regular expression \d+, which ensures that it consists of one or more digits (i.e., a positive integer).
// The controller method list() takes an integer parameter $page, which corresponds to the value of the page parameter extracted from the URL.
// Inside the controller method, you can use the $page parameter to fetch and display articles for the specified page number.



//                ======================== Parameter priority ========================================================
// src/Controller/ArticleController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class ArticleController extends AbstractController
// {
//     #[Route('/articles/{slug}', name: 'article_show', priority: 1)]
//     public function show(string $slug): Response
//     {
//         // Controller logic to fetch and display the article with the given slug

//         return $this->render('article/show.html.twig', [
//             'slug' => $slug,
//         ]);
//     }

//     #[Route('/articles', name: 'article_list', priority: 0)]
//     public function list(): Response
//     {
//         // Controller logic to fetch and display a list of articles

//         return $this->render('article/list.html.twig');
//     }
// }



// We define two routes using annotations: one for displaying individual articles (/articles/{slug}) and one for displaying a list of articles (/articles).
// The route for displaying individual articles has a higher priority (priority: 1) than the route for displaying the list of articles (priority: 0).
// By setting the priority parameter, we ensure that Symfony checks the route for displaying individual articles (article_show) before the route for displaying the list of articles (article_list).
// As a result, when a user visits a URL like /articles/my-article, Symfony will match the route for displaying individual articles (article_show), and the show() method will be executed. If the priority parameter is not set, Symfony may match the route for displaying the list of articles (article_list) instead.



// = = == === ===== ==== ===== ==== === ===== === == Getting the Route Name and Parameters = = = = = = = = = = = = = = = = = = =

// The Request object created by Symfony stores all the route configuration (such as the name and parameters) in the "request attributes". You can get this information in a controller via the Request object:

//  Copy
// // src/Controller/BlogController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// class BlogController extends AbstractController
// {
//     #[Route('/blog', name: 'blog_list')]
//     public function list(Request $request): Response
//     {
//         $routeName = $request->attributes->get('_route');
//         $routeParameters = $request->attributes->get('_route_params');

//         // use this to get all the available attributes (not only routing ones):
//         $allAttributes = $request->attributes->all();

//         // ...
//     }
// }
// In services, you can get this information by injecting the RequestStack service. In templates, use the Twig global app variable to get the current route name (app.current_route) and its parameters (app.current_route_parameters).


// ============================================== special parameters ================================

// _controller: This parameter specifies which controller method will be executed when the route is matched. It essentially tells Symfony which code to run to handle the request.

// _format: This parameter determines the format of the response. For example, if the _format is set to "json", Symfony will set the Content-Type of the response to "application/json". It helps in delivering responses in different formats based on the request.

// _fragment: This parameter sets the fragment identifier of the URL. It's the optional part of a URL that starts with a "#" character and is used to identify a specific section within a document. However, this parameter is not commonly used in route definitions.

// _locale: This parameter sets the locale (language) on the request. It helps in internationalization and localization of the application, allowing content to be served in different languages based on user preferences.

// Here's a simple example to demonstrate the usage of these special parameters in Symfony routing:

// src/Controller/ArticleController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class ArticleController extends AbstractController
// {
//     #[Route(
//         path: '/articles/{_locale}/search.{_format}',
//         locale: 'en',
//         format: 'html',
//         requirements: [
//             '_locale' => 'en|fr',
//             '_format' => 'html|xml',
//         ],
//     )]
//     public function search(): Response
//     {
//         // Controller logic for searching articles
//     }
// }


// ============================================== Prefixes ================================================ 

// It's common for a group of routes to share some options (e.g. all routes related to the blog start with /blog) That's why Symfony includes a feature to share route configuration.

// When defining routes as attributes, put the common configuration in the #[Route] attribute of the controller class. In other routing formats, define the common configuration using options when importing the routes.

//  Copy
// // src/Controller/BlogController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// #[Route('/blog', requirements: ['_locale' => 'en|es|fr'], name: 'blog_')]
// class BlogController extends AbstractController
// {
//     #[Route('/{_locale}', name: 'index')]
//     public function index(): Response
//     {
//         // ...
//     }

//     #[Route('/{_locale}/posts/{slug}', name: 'show')]
//     public function show(string $slug): Response
//     {
//         // ...
//     }
// }




// Getting Route Information in a Controller

// When Symfony handles a request, it creates a Request object that contains all the information about the current request, including the route name and any parameters.

// Here’s an example to understand how to access this information in a controller:

// php

// // src/Controller/BlogController.php
// namespace App\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Annotation\Route;

// class BlogController extends AbstractController
// {
//     #[Route('/blog', name: 'blog_list')]
//     public function list(Request $request): Response
//     {
//         // Get the name of the current route
//         $routeName = $request->attributes->get('_route');

//         // Get the parameters of the current route (if any)
//         $routeParameters = $request->attributes->get('_route_params');

//         // Get all the request attributes, which includes routing and other attributes
//         $allAttributes = $request->attributes->all();

//         // Do something with this information...
//         // For example, logging or passing it to the view

//         return new Response(
//             '<html><body>Route name: ' . $routeName . '<br>Route parameters: ' . json_encode($routeParameters) . '</body></html>'
//         );
//     }
// }

// ============================================= Get route name =========================== 

//     Route Name: blog_list is the name of the route, which you defined with name: 'blog_list' in the #[Route] attribute.

//     php

// $routeName = $request->attributes->get('_route');

// This line gets the name of the current route and stores it in the $routeName variable.

// Route Parameters: Routes can have parameters (like {slug} or {id}). In this example, there are no parameters, but you can still retrieve them using:

// php

// $routeParameters = $request->attributes->get('_route_params');

// This line gets any parameters passed in the route and stores them in the $routeParameters variable.

// All Attributes: To get all the attributes available in the Request object (including routing and other attributes), you use:

// php

//     $allAttributes = $request->attributes->all();

// Using Route Information in Services or Templates

//     In Services: You can access the current request information by injecting the RequestStack service.

//     In Templates: You can use Twig to access the route name and parameters.

//     twig

//     {# Twig template example #}
//     <p>Current route name: {{ app.request.attributes.get('_route') }}</p>
//     <p>Current route parameters: {{ app.request.attributes.get('_route_params')|json_encode }}</p>

// This is how you can access and use route information in your Symfony application. It helps in debugging, logging, or making decisions based on the current route.


