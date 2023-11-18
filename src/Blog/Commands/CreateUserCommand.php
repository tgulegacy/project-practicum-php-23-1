<?php

namespace Tgu\Aksenov\Blog\Commands;

use Psr\Log\LoggerInterface;
use Tgu\Aksenov\Blog\Exceptions\CommandException;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Person\Name;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

class CreateUserCommand
{
	public function __construct(
		private UserRepositoryInterface $userRepository,
		private LoggerInterface $logger,
	)
	{
		
	}

	public function handle(Arguments $arguments): void
	{
		$this->logger->info("Create user commant started");

		$username = $arguments->get('username');

		if ($this->userExisit($username)) {
			throw new CommandException(
				"User already exists: $username"
			);
			$this->logger->warning("User already exists: $username");
			return;
		}

		$user = User::createFrom(
			$username,
			$arguments->get('password'),
			new Name(
				$arguments->get('first_name'),
			 $arguments->get('last_name')
			)
		);

		$this->userRepository->save($user);

		$this->logger->info("User created: " . $user->getUuid());
	}

	public function userExisit(string $username): bool
	{
		try {
			$user = $this->userRepository->getByUsername($username);
		} catch (UserNotFoundException) {
			return false;
		}

		return true;
	}
}