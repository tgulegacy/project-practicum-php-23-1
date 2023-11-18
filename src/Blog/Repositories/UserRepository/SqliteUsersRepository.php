<?php
namespace Tgu\Aksenov\Blog\Repositories\UserRepository;

use PDO;
use PDOStatement;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Person\Name;
use Tgu\Aksenov\Blog\UUID;

class SqliteUsersRepository implements UserRepositoryInterface
{
	public function __construct(
		private PDO $connection,
	)
	{}

	public function save(User $user): void
	{
		$statement = $this->connection->prepare(
			'INSERT INTO users (uuid, username, first_name, last_name, password) VALUES (:uuid, :username, :first_name, :last_name, :password)'
		);

		$statement->execute([
			':uuid' => (string)$user->getUuid(),
			':username' => $user->getUsername(),
			':first_name' => $user->getName()->getFirstName(),
			':last_name' => $user->getName()->getLastName(),
			':password' => $user->getHashedPassword(),
		]);
	}

	public function get(UUID $uuid): User
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM users WHERE uuid = :uuid'
		);
		$statement->execute([
			':uuid' => (string)$uuid,
		]);

		return $this->getUser($statement, (string)$uuid);
	}

	public function getByUsername(string $username): User
	{
		$statement = $this->connection->prepare(
			'SELECT * FROM users WHERE username = :username'
		);
		$statement->execute([
			':username' => $username,
		]);

		return $this->getUser($statement, $username);
	}

	private function getUser(PDOStatement $statement, string $payload)
	{
		$result = $statement->fetch(PDO::FETCH_ASSOC);

		if ($result === false) {
			throw new UserNotFoundException(
				"Cannot get user: $payload"
			);
		}

		return new User(
			new UUID($result['uuid']),
			$result['username'],
			$result['password'],
			new Name($result['first_name'], $result['last_name'])
		);
	}
}