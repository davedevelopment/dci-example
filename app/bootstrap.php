<?php

require __DIR__."/../vendor/autoload.php";

use BookShop\Application;
use Symfony\Component\HttpFoundation\Request;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\Security\User\EntityUserProvider;
use BookShop\Repository\UserRepository;
use BookShop\Entity\Book;

/**
 * Bootstrap application
 */
$app = new Application;
$app['debug'] = true;
$app->register(new SessionServiceProvider);
$app->register(new UrlGeneratorServiceProvider);
$app->register(new DoctrineServiceProvider, array(
    'db.options' => array(
        'driver' => 'pdo_sqlite',
        'path' => __DIR__.'/../data/app.db',
    ),
));

$app->register(new SecurityServiceProvider, array(
    'security.firewalls' => array(
        'site' => array(
            'pattern' => "^/",
            'http' => true,
            'users' => $app->share(function() use ($app) {
                return $app['em']->getRepository("BookShop\Entity\User"); 
            }),
        ),
    ),
));

/**
 * Define the entity manager, using our yaml mapping files
 */
$app['em'] = $app->share(function() use ($app) {
    $config = Setup::createYamlMetadataConfiguration(array(
        __DIR__.'/config/doctrine',
    ), true);
    return EntityManager::create($app['db'], $config);
});

/**
 * Controllers
 */

$app->get("/", function() {
    return 'Book shop';
})->bind("home");

$app->get("/book/{book}", function(Book $book) use ($app) {

    $html = <<<EOS

{$book->getName()}

<form action="{$app->path("add_to_cart")}" method="POST">
    <input type="hidden" name="id" value="{$book->getId()}">
    <input type="submit" value="Add to Cart">
</form>

EOS;

    return $html;

})->convert("book", function($id) use ($app) {

    $book = $app['em']->find("BookShop\Entity\Book", $id);
    if (!$book) {
        return $app->abort(404, "Book not found");
    }
    return $book;

})->bind("book_view");

$app->post("/cart", function(Book $book) use ($app) {

    (new BookShop\Context\AddToCartContext($app->user(), $book))->execute();
    $app['em']->flush();
    return $app->redirect($app->path("book_view", array("book" => $book->getId())));

})->convert("book", function($book, Request $request) use ($app) {

    $book = $app['em']->find("BookShop\Entity\Book", $request->get("id"));
    if (!$book) {
        return $app->abort(400, "Book not found");
    }
    return $book;

})->bind("add_to_cart");

return $app;
