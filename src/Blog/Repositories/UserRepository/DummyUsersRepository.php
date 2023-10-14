<?php
namespace Tgu\Aksenov\Blog\Repositories\UserRepository;

use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Person\Name;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

class DummyUsersRepository implements UserRepositoryInterface
{
	public function save(User $user): void
	{
		// ничего не делает
	}

	public function get(UUID $uuid): User
	{
		throw new UserNotFoundException("Not found");
	}

	public function getByUsername(string $username): User
	{
		return new User(UUID::random(), "Ivan", new Name('first', 'last'));
	}
}