<?php

use Tgu\Aksenov\Blog\Commands\Arguments;
use Tgu\Aksenov\Blog\Commands\CreateUserCommand;
use Tgu\Aksenov\Blog\Exceptions\CommandException;

$container = require __DIR__ . '/bootstrap.php';

$command = $container->get(CreateUserCommand::class);

try {
	$command->handle(Arguments::fromArgv($argv));
} catch (CommandException $error) {
	echo "{$error->getMessage()}\n";
}