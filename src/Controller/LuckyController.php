<?php

//src/Controller/LuckyController.php
namespace App\Controller;

// use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

class LuckyController
{
//    #[Route('/lucky/number')]  -- using anotations
    public function number(): Response
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body><h1>Lucky number: '.$number.'</h1></body></html>'
        );
    }
}

// We can debug routes using : php bin/console debug:router //