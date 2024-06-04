<!-- A common need for templates is to print the values stored in the templates passed from the controller or service. Variables usually store objects and arrays instead of strings, numbers and boolean values. That's why Twig provides quick access to complex PHP variables. Consider the following template:


<p>{{ user.name }} added this comment on {{ comment.publishedAt|date }}</p>
The user.name notation means that you want to display some information (name) stored in a variable (user). Is user an array or an object? Is name a property or a method? In Twig this doesn't matter.

When using the foo.bar notation, Twig
tries to get the value of the variable in the following order:

$foo['bar'] (array and element);
$foo->bar (object and public property);
$foo->bar() (object and public method);
$foo->getBar() (object and getter method);
$foo->isBar() (object and isser method);
$foo->hasBar() (object and hasser method); -->



<!-- n your controller, you set up routes which tell Symfony how to respond to different URLs. Here's a simple example:

php

// src/Controller/BlogController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/', name: 'blog_index')]
    public function index(): Response
    {
        return new Response('This is the homepage.');
    }

    #[Route('/article/{slug}', name: 'blog_post')]
    public function show(string $slug): Response
    {
        return new Response('This is the blog post: ' . $slug);
    }
}

    #[Route('/', name: 'blog_index')]: This defines the homepage route.
    #[Route('/article/{slug}', name: 'blog_post')]: This defines the route for a blog post where {slug} is a placeholder for the specific post's identifier.

Step 2: Use path() in Your Twig Template

Instead of hardcoding URLs, use the path() function in Twig templates to generate URLs based on the route names you defined.

Here’s how you can link to these pages:

twig

{# templates/blog/index.html.twig #}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>
</head>
<body>
    <a href="{{ path('blog_index') }}">Homepage</a>

    {# Loop through blog posts and generate links #}
    {% for post in blog_posts %}
        <h1>
            <a href="{{ path('blog_post', {slug: post.slug}) }}">{{ post.title }}</a>
        </h1>
        <p>{{ post.excerpt }}</p>
    {% endfor %}
</body>
</html>

Explanation

    {{ path('blog_index') }}: Generates the URL for the homepage. If you change the route in the controller, this URL updates automatically.
    {{ path('blog_post', {slug: post.slug}) }}: Generates the URL for a specific blog post. The {slug: post.slug} part passes the slug of the current post to the URL.

Using url() for Absolute URLs

For generating full URLs (useful in emails or RSS feeds), use the url() function:

twig

<a href="{{ url('blog_index') }}">Homepage</a>

    {{ url('blog_index') }}: Generates the full URL for the homepage. -->

<!--     ===================================== Linking ==========================================


    Linking to CSS, JavaScript and Image Assets
If a template needs to link to a static asset (e.g. an image), Symfony provides an asset() Twig function to help generate that URL. First, install the asset package:

composer require symfony/asset
You can now use the asset() function:

 Copy
{# the image lives at "public/images/logo.png" #}
<img src="{{ asset('images/logo.png') }}" alt="Symfony!"/>

{# the CSS file lives at "public/css/blog.css" #}
<link href="{{ asset('css/blog.css') }}" rel="stylesheet"/>

{# the JS file lives at "public/bundles/acme/js/loader.js" #}
<script src="{{ asset('bundles/acme/js/loader.js') }}"></script>
The asset() function's main purpose is to make your application more portable. If your application lives at the root of your host (e.g. https://example.com), then the rendered path should be /images/logo.png. But if your application lives in a subdirectory (e.g. https://example.com/my_app), each asset path should render with the subdirectory (e.g. /my_app/images/logo.png). The asset() function takes care of this by determining how your application is being used and generating the correct paths accordingly. -->


<!-- =============================================== Twig Components ================================================== 
Twig components are an alternative way to render templates, where each template is bound to a "component class". This makes it easier to render and re-use small template "units" - like an alert, markup for a modal, or a category sidebar.

For more information, see UX Twig Component.

Twig components also have one other superpower: they can become "live", where they automatically update (via Ajax) as the user interacts with them. For example, when your user types into a box, your Twig component will re-render via Ajax to show a list of results!
-->

<!-- ======================================= Debugging templates ======================================================= -->

<!-- Symfony provides several utilities to help you debug issues in your templates.

Linting Twig Templates
The lint:twig command checks that your Twig templates don't have any syntax errors. It's useful to run it before deploying your application to production (e.g. in your continuous integration server):


php bin/console lint:twig


php bin/console lint:twig templates/email/
php bin/console lint:twig templates/article/recent_list.html.twig


php bin/console lint:twig --show-deprecations templates/email/


php bin/console lint:twig templates/ --excludes=data_collector --excludes=dev_tool
7.1
The option to exclude directories was introduced in Symfony 7.1.

When running the linter inside GitHub Actions, the output is automatically adapted to the format required by GitHub, but you can force that format too: -->

<!-- ===================================== Check if template exists ======================================================= -->
<!-- 
Checking if a Template Exists
Templates are loaded in the application using a Twig template loader, which also provides a method to check for template existence. First, get the loader:

use Twig\Environment;

class YourService
{
    // this code assumes that your service uses autowiring to inject dependencies
    // otherwise, inject the service called 'twig' manually
    public function __construct(Environment $twig)
    {
        $loader = $twig->getLoader();
    }
}
Then, pass the path of the Twig template to the exists() method of the loader:

if ($loader->exists('theme/layout_responsive.html.twig')) {
    // the template exists, do something
    // ...
} -->









