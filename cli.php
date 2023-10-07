<?php

use Tgu\Aksenov\Blog\Commands\Arguments;
use Tgu\Aksenov\Blog\Commands\CreateUserCommand;
use Tgu\Aksenov\Blog\Exceptions\CommandException;
use Tgu\Aksenov\Blog\Repositories\UserRepository\SqliteUsersRepository;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$userRepository = new SqliteUsersRepository($connection);

$command = new CreateUserCommand($userRepository);

try {
	$command->handle(Arguments::fromArgv($argv));
} catch (CommandException $error) {
	echo "{$error->getMessage()}\n";
}