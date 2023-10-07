<?php
namespace Tgu\Aksenov\Blog;

use Tgu\Aksenov\Blog\Person\Name;

class User
{
	public function __construct(
		private UUID $uuid,
		private string $username,
		private Name $name,
	)
	{
		
	}

	public function getUuid(): UUID
	{
		return $this->uuid;
	}

	public function getUsername(): string
	{
		return $this->username;
	}

	public function getName(): Name
	{
		return $this->name;
	}
}