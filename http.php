<?php

require_once __DIR__ . '/vendor/autoload.php';

use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Http\Actions\Users\CreateUser;
use Tgu\Aksenov\Blog\Http\Actions\Users\FindByUsername;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Repositories\UserRepository\SqliteUsersRepository;

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
		'/users/show' => new FindByUsername(
			new SqliteUsersRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			)
		),
	],
	'POST' => [
		'/users/create' => new CreateUser(
			new SqliteUsersRepository(
				new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
			)
		),
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

$action = $routes[$method][$path];

try {
	$response = $action->handle($request);
} catch (Exception $error) {
	(new ErrorResponse($error->getMessage()))->send();
}

$response->send();