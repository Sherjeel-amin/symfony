<?php

// ====================================================== Controller ============================================================

// src/Controller/LuckyController.php
 
// namespace App\Controller;

// use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\Routing\Attribute\Route;

// class LuckyController
// {
//     #[Route('/lucky/number/{max}', name: 'app_lucky_number')]
//     public function number(int $max): Response
//     {
//         $number = random_int(0, $max);

//         return new Response(
//             '<html><body>Lucky number: '.$number.'</body></html>'
//         );
//     }
// }
?>
 <!-- While a controller can be any PHP callable (function, method on an object, or a Closure), a controller is usually a method inside a controller class: -->
<!-- The controller is the number() method, which lives inside the controller class LuckyController -->


<!-- ============================================= Redirecting ================================================================


If you want to redirect the user to another page, use the redirectToRoute() and redirect() methods:

 Copy
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

// ...
public function index(): RedirectResponse
{
    // redirects to the "homepage" route
    return $this->redirectToRoute('homepage');

    // redirectToRoute is a shortcut for:
    // return new RedirectResponse($this->generateUrl('homepage'));

    // does a permanent HTTP 301 redirect
    return $this->redirectToRoute('homepage', [], 301);
    // if you prefer, you can use PHP constants instead of hardcoded numbers
    return $this->redirectToRoute('homepage', [], Response::HTTP_MOVED_PERMANENTLY);

    // redirect to a route with parameters
    return $this->redirectToRoute('app_lucky_number', ['max' => 10]);

    // redirects to a route and maintains the original query string parameters
    return $this->redirectToRoute('blog_show', $request->query->all());

    // redirects to the current route (e.g. for Post/Redirect/Get pattern):
    return $this->redirectToRoute($request->attributes->get('_route'));

    // redirects externally
    return $this->redirect('http://symfony.com/doc');
} -->


<!-- =================================================================== Services ================================ -->

<!-- 
Symfony, a PHP framework, provides lots of helpful tools called services. These services can do all sorts of tasks like showing web pages, sending emails, or getting information from a database.

When you're working in a Symfony controller (which handles requests and sends back responses), you might need one of these services. To get one, you can simply ask for it by mentioning its name or what it does.

For example, if you need to log some information, you can ask Symfony to give you a "logger" service by just mentioning LoggerInterface $logger in your controller function. Symfony will automatically give you what you asked for.

But there are many other services available, and if you're not sure what they are, you can ask Symfony to list them for you. You just need to run a command like php bin/console debug:autowiring, and it will show you a list of all available services.

Sometimes, you might need more control over which service you get or you might want to pass some specific information to the service. In such cases, Symfony provides a special way to do this using an attribute called Autowire.

For example, if you want a particular type of logger or if you need to pass some specific information like the project directory, you can do that using Autowire. This makes it easy to customize how your services work in different parts of your application.

So, in simple terms, Symfony services are like tools that help you do different tasks in your web application, and Symfony makes it easy for you to use these tools wherever you need them. -->


<!-- Suppose you have a Symfony controller that needs to log some information and also needs to access the project directory. You can use services to achieve this. Here's how you could do it:

php

// Import necessary classes
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;

// Define your controller class
class ExampleController extends AbstractController
{
    // Define a controller function
    public function someAction(
        LoggerInterface $logger, // Symfony will automatically provide a logger service
        #[Autowire(service: 'monolog.logger.request')] LoggerInterface $specificLogger,
        #[Autowire('%kernel.project_dir%')] string $projectDir
    ): Response {
        // Now you have access to logger services and the project directory

        // Logging some information using the provided logger service
        $logger->info('Logging information using the default logger');

        // Logging some information using the specific logger service
        $specificLogger->info('Logging information using the specific logger');

        // Using the project directory
        $message = 'The project directory is: ' . $projectDir;

        // Returning a simple response
        return new Response($message);
    }
}

In this example:

    We define a controller class ExampleController.
    Inside this class, we define a function someAction. This function represents the action that will be executed when this controller is accessed.
    We type-hint three arguments:
        LoggerInterface $logger: Symfony automatically provides a default logger service.
        #[Autowire(service: 'monolog.logger.request')] LoggerInterface $specificLogger: We specifically ask Symfony to provide a logger service named monolog.logger.request.
        #[Autowire('%kernel.project_dir%')] string $projectDir: We ask Symfony to provide the project directory path as a string.
    Inside the function, we use these injected services:
        We use both loggers to log some information.
        We use the project directory path to construct a message.
    Finally, we return a simple HTTP response with the constructed message.

This way, Symfony takes care of providing the necessary services to your controller, making your code cleaner and more manageable. -->


<!-- ======================================= Generating Controllers ==============================================  -->

<!-- Imagine you're building a web application using Symfony, and you need to create some controllers to handle different parts of your application, like managing products in an online store. Instead of writing all the code manually, Symfony provides a tool called Symfony Maker to help you generate controllers quickly.

Let's say you want to create a controller called BrandNewController. You can use Symfony Maker to generate it for you with just one command:

bash

php bin/console make:controller BrandNewController

When you run this command, Symfony generates a new controller class named BrandNewController and also creates a default template file for it. Here's how the generated code might look:

php

// src/Controller/BrandNewController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrandNewController extends AbstractController
{
    #[Route('/brand-new', name: 'brand_new')]
    public function index(): Response
    {
        return $this->render('brandnew/index.html.twig', [
            'controller_name' => 'BrandNewController',
        ]);
    }
}

This code does a few things:

    It defines a new controller class named BrandNewController in the src/Controller directory.
    Inside the class, there's a function called index, which represents the action that will be executed when someone accesses the URL /brand-new.
    The index function returns a response, typically an HTML page. In this case, it renders the brandnew/index.html.twig template and passes some data to it.

Along with the controller class, Symfony also creates a default template file index.html.twig in the templates/brandnew directory. This template can be customized to display whatever content you want.

This way, Symfony Maker helps you quickly create controllers and templates, saving you time and effort when building your Symfony application. -->


<!-- ===================================== Managing errors and 404 pages ==================================== -->

<!-- Managing Errors and 404 Pages
When things are not found, you should return a 404 response. To do this, throw a special type of exception:

 Copy
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

// ...
public function index(): Response
{
    // retrieve the object from database
    $product = ...;
    if (!$product) {
        throw $this->createNotFoundException('The product does not exist');

        // the above is just a shortcut for:
        // throw new NotFoundHttpException('The product does not exist');
    }

    return $this->render(/* ... */);
}
The createNotFoundException() method is just a shortcut to create a special NotFoundHttpException object, which ultimately triggers a 404 HTTP response inside Symfony.

If you throw an exception that extends or is an instance of HttpException, Symfony will use the appropriate HTTP status code. Otherwise, the response will have a 500 HTTP status code:

 
// this exception ultimately generates a 500 status error
throw new \Exception('Something went wrong!');
In every case, an error page is shown to the end user and a full debug error page is shown to the developer (i.e. when you're in "Debug" mode - see Configuring Symfony).

To customize the error page that's shown to the user, see the How to Customize Error Pages article. -->