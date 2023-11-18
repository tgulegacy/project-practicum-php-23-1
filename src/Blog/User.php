<?php
namespace Tgu\Aksenov\Blog;

use Tgu\Aksenov\Blog\Person\Name;

class User
{
	public function __construct(
		private UUID $uuid,
		private string $username,
		private string $hashedPassword,
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

	public function getHashedPassword(): string
	{
		return $this->hashedPassword;
	}

	private static function hash(string $password, UUID $uuid): string
	{
		return hash('sha256', $uuid . $password);
	}

	public function chackPassword(string $password): bool
	{
		return $this->hashedPassword === self::hash($password, $this->getUuid());
	}

	public function getName(): Name
	{
		return $this->name;
	}

	public static function createFrom(
		string $username,
		string $password,
		Name $name,
	): self
	{
		$uuid = UUID::random();
		return new self(
			$uuid,
			$username,
			self::hash($password, $uuid),
			$name
		);
	}

	public function __toString(): string
	{
		return (string)$this->name;
	}
}