<!-- Introduction to ORM

    Definition: Object-Relational Mapping (ORM) is a technique for converting data between incompatible type systems (object-oriented programming languages and relational databases).
    Purpose: Simplifies database interactions by using objects instead of SQL queries.
    Benefits: Reduces boilerplate code, maintains consistency, and improves productivity.

Doctrine ORM in Symfony

    Definition: Doctrine ORM is a powerful library for database abstraction, allowing you to work with database records as PHP objects.
    Integration: Doctrine ORM is integrated into Symfony to facilitate database operations in an object-oriented manner.

Setting Up Doctrine in Symfony
Installation

    Install Doctrine and the Symfony Maker Bundle:

    bash

    composer require symfony/orm-pack
    composer require --dev symfony/maker-bundle

Configuration

    Configure Doctrine in config/packages/doctrine.yaml:

    yaml

    doctrine:
        dbal:
            driver: 'pdo_mysql'
            server_version: '5.7'
            charset: utf8mb4
            url: '%env(resolve:DATABASE_URL)%'
        orm:
            auto_generate_proxy_classes: true
            naming_strategy: doctrine.orm.naming_strategy.underscore_number_aware
            auto_mapping: true

Creating and Managing Entities
Creating Entities

    Use the Maker Bundle to create an entity:

    bash

    php bin/console make:entity

    This command will guide you through creating a new entity with properties and their types.

Persisting Data

    Use the EntityManager to save an object to the database:

    php

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($entity);
    $entityManager->flush();

Fetching Data

    Use repository classes to fetch data:

    php

    $repository = $this->getDoctrine()->getRepository(User::class);
    $users = $repository->findAll();

Doctrine Migrations
Creating Migrations

    Generate a new migration after making changes to entities:

    bash

    php bin/console make:migration
    php bin/console doctrine:migrations:migrate

Advanced Doctrine Features
Custom Mapping Types

    Define custom mapping types to handle special database types like enums​ (Symfony)​.

Lifecycle Callbacks

    Use lifecycle callbacks to hook into specific entity events (e.g., prePersist, postUpdate):

    php

    /**
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        // Your code here
    }

    ​ (Symfony)​

Event Listeners and Subscribers

    Hook into entity lifecycle events using Doctrine event listeners and subscribers​ (Symfony)​.

Generating Entities from Existing Database

    Although the doctrine:mapping:import command is deprecated, manually create entities or use the make:entity command to generate entity code for exi -->

<!-- 
    Command to Create an Entity:

bash

php bin/console make:entity

Generated Entity (src/Entity/Product.php):

php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    // Getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;
        return $this;
    }
}

Persisting Data

Persisting a New Entity:

php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/create', name: 'create_product')]
    public function create(EntityManagerInterface $em): Response
    {
        $product = new Product();
        $product->setName('Example Product');
        $product->setPrice(19.99);

        $em->persist($product);
        $em->flush();

        return new Response('Product created with ID ' . $product->getId());
    }
}

Fetching Data

Fetching Data with Repository:

php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'product_show')]
    public function show($id): Response
    {
        $product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        return new Response('Product: ' . $product->getName() . ', Price: ' . $product->getPrice());
    }
}

Updating Data

Updating an Entity:

php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/update/{id}', name: 'update_product')]
    public function update($id, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $product->setName('Updated Product');
        $em->flush();

        return new Response('Product updated with new name: ' . $product->getName());
    }
}

Deleting Data

Deleting an Entity:

php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/delete/{id}', name: 'delete_product')]
    public function delete($id, EntityManagerInterface $em): Response
    {
        $product = $em->getRepository(Product::class)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $em->remove($product);
        $em->flush();

        return new Response('Product deleted');
    }
}

Using Migrations

Creating and Running Migrations:

bash

php bin/console make:migration
php bin/console doctrine:migrations:migrate

Custom Mapping Types

Custom Mapping Type Example:

php

// src/Doctrine/EnumType.php
namespace App\Doctrine;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

class EnumType extends Type
{
    const ENUM = 'enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "ENUM('value1', 'value2', 'value3')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return (string) $value;
    }

    public function getName()
    {
        return self::ENUM;
    }
}

Register Custom Type:

yaml

# config/packages/doctrine.yaml
doctrine:
    dbal:
        types:
            enum: App\Doctrine\EnumType

Lifecycle Callbacks

Lifecycle Callbacks Example:

php

// src/Entity/Product.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    // Fields and methods

    /**
     * @ORM\PrePersist
     */
    public function doStuffOnPrePersist()
    {
        // Your code here
    }
} -->
