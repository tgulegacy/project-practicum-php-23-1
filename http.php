<?php

use Psr\Log\LoggerInterface;
use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Http\Actions\Auth\Login;
use Tgu\Aksenov\Blog\Http\Actions\Posts\CreatePost;
use Tgu\Aksenov\Blog\Http\Actions\Users\CreateUser;
use Tgu\Aksenov\Blog\Http\Actions\Users\FindByUsername;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));

$logger = $container->get(LoggerInterface::class);

try {
	$path = $request->path();
} catch (HttpException) {
	$logger->warning($error->getMessage());
	(new ErrorResponse)->send();
	return;
}

try {
	$method = $request->method();
} catch (HttpException) {
	$logger->warning($error->getMessage());
	(new ErrorResponse)->send();
	return;
}

$routes = [
	'GET' => [
		'/users/show' => FindByUsername::class,
	],
	'POST' => [
		'/users/create' => CreateUser::class,
		'/posts/create' => CreatePost::class,
		'/login' => Login::class,
	]
];

if (!array_key_exists($method, $routes) || !array_key_exists($path, $routes[$method])) {
	$message = "Route not found: $method $path";
	$logger->notice($message);
	(new ErrorResponse($message))->send();
	return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
	$response = $action->handle($request);
} catch (Exception $error) {
	$logger->error($error->getMessage(), ['exception' => $error]);
	(new ErrorResponse)->send();
}

$response->send();