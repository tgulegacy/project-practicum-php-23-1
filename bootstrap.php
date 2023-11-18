<?php

use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Tgu\Aksenov\Blog\Container\DIContainer;
use Tgu\Aksenov\Blog\Http\Auth\AuthenticationInterface;
use Tgu\Aksenov\Blog\Http\Auth\BearerTokenAuthentication;
use Tgu\Aksenov\Blog\Http\Auth\PasswordAuthentication;
use Tgu\Aksenov\Blog\Http\Auth\PasswordAuthenticationInterface;
use Tgu\Aksenov\Blog\Http\Auth\TokenAuthenticationInterface;
use Tgu\Aksenov\Blog\Repositories\AuthTokenRepository\AuthTokenRepositoryInterface;
use Tgu\Aksenov\Blog\Repositories\AuthTokenRepository\SqliteAuthTokenREpository;
use Tgu\Aksenov\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Tgu\Aksenov\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use Tgu\Aksenov\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$container = new DIContainer;

$container->bind(PDO::class, new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH']));

$container->bind(UserRepositoryInterface::class, SqliteUsersRepository::class);

$container->bind(PostsRepositoryInterface::class, SqlitePostsRepository::class);

$container->bind(PasswordAuthenticationInterface::class, PasswordAuthentication::class);

$container->bind(AuthTokenRepositoryInterface::class, SqliteAuthTokenREpository::class);

$container->bind(TokenAuthenticationInterface::class, BearerTokenAuthentication::class);

$logger = (new Logger('blog'));

if ($_SERVER['LOG_TO_FILES'] === 'yes') {
	$logger->pushHandler(new StreamHandler(__DIR__ . '/logs/blog.log'))
		->pushHandler(new StreamHandler(
			__DIR__ . '/logs/blog.error.log', 
			level: Level::Error, 
			bubble: false
	));
}

if ($_SERVER['LOG_TO_CONSOLE'] === 'yes') {
	$logger->pushHandler(new StreamHandler("php://stdout"));
}

$container->bind(LoggerInterface::class, $logger);

return $container;
