<?php

use Tgu\Aksenov\Blog\Container\DIContainer;
use Tgu\Aksenov\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = new DIContainer;

$container->bind(PDO::class, new PDO('sqlite:' . __DIR__ . '/blog.sqlite'));

$container->bind(UserRepositoryInterface::class, SqliteUsersRepository::class);

return $container;
