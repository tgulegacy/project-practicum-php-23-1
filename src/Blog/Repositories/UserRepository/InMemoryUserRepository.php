<?php
namespace Tgu\Aksenov\Blog\Repositories\UserRepository;

use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\UUID;

class InMemoryUserRepository implements UserRepositoryInterface
{
	private array $users = [];

	public function save(User $user): void
	{
		$this->users[] = $user;
	}

	public function get(UUID $uuid): User
	{
		foreach ($this->users as $user) {
			if ((string)$user->uuid() === (string)$uuid) {
				return $user;
			}
		}

		throw new UserNotFoundException("User not found: $uuid");
	}

	public function getByUsername(string $username): User
	{
		foreach ($this->users as $user) {
			if ($user->getUsername() === $username) {
				return $user;
			}
		}

		throw new UserNotFoundException("User not found: $username");
	}
}