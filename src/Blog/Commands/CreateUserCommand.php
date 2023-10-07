<?php

namespace Tgu\Aksenov\Blog\Commands;

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
	)
	{
		
	}

	public function handle(Arguments $arguments): void
	{
		$username = $arguments->get('username');

		if ($this->userExisit($username)) {
			throw new CommandException(
				"User already exists: $username"
			);
		}

		$this->userRepository->save(new User(
			UUID::random(),
			$username,
			new Name(
				$arguments->get('first_name'),
			 $arguments->get('last_name')
			)
		));
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