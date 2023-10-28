<?php

use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Http\Actions\Users\CreateUser;
use Tgu\Aksenov\Blog\Http\Actions\Users\FindByUsername;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;

$container = require __DIR__ . '/bootstrap.php';

$request = new Request($_GET, $_SERVER, file_get_contents('php://input'));

try {
	$path = $request->path();
} catch (HttpException) {
	(new ErrorResponse)->send();
	return;
}

try {
	$method = $request->method();
} catch (HttpException) {
	(new ErrorResponse)->send();
	return;
}

$routes = [
	'GET' => [
		'/users/show' => FindByUsername::class,
	],
	'POST' => [
		'/users/create' => CreateUser::class
	]
];

if (!array_key_exists($method, $routes)) {
	(new ErrorResponse('Not found'))->send();
	return;
}

if (!array_key_exists($path, $routes[$method])) {
	(new ErrorResponse('Not found'))->send();
	return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
	$response = $action->handle($request);
} catch (Exception $error) {
	(new ErrorResponse($error->getMessage()))->send();
}

$response->send();