<?php

$app = require __DIR__.'/app/bootstrap.php';

$helperSet = new \Symfony\Component\Console\Helper\HelperSet(array(
    'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app['db']),
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['em'])
));
