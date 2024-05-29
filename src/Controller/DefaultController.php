<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DefaultController extends AbstractController
{
    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        // return $this->json(["username" => "Sherjeel"]);
        // return new Response("hello!  $name" );
        return $this->redirectToRoute("default2");
    }

    #[Route('/default2', name: 'default2')]
    public function index2(): Response
    {
        return new Response("Hello from 2");  //--> we can use redirection as well from one method to another
    }
}

