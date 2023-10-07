<?php
namespace Tgu\Aksenov\Blog\Repositories\UserRepository;

use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

interface UserRepositoryInterface
{
	public function save(User $user): void;

	public function get(UUID $uuid): User;

	public function getByUsername(string $username): User;
}