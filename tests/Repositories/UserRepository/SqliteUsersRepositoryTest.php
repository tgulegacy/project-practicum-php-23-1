<?php
namespace Tgu\Aksenov\Blog\UnitTests\Repositories\UserRepository;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Person\Name;
use Tgu\Aksenov\Blog\Repositories\UserRepository\SqliteUsersRepository;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

class SqliteUsersRepositoryTest extends TestCase
{
	public function testItThrowsAnExceptionWhenUserNotFound(): void
	{
		$connectionStub = $this->createStub(PDO::class);

		$statmentStub = $this->createStub(PDOStatement::class);

		$statmentStub->method('fetch')->willReturn(false);

		$connectionStub->method('prepare')->willReturn($statmentStub);

		$repository = new SqliteUsersRepository($connectionStub);

		$this->expectException(UserNotFoundException::class);
		$this->expectExceptionMessage('Cannot get user: Ivan');

		$repository->getByUsername('Ivan');
	}

	public function testItSaveUserToDatabase(): void
	{
		$connectionStub = $this->createStub(PDO::class);

		$statmentMock = $this->createMock(PDOStatement::class);

		$statmentMock->expects($this->once())->method('execute')->with([
			':uuid' => '0d5440ef-38d4-420b-bd1c-f882aeb18343',
			':username' => 'ivan123',
			':first_name' => 'ivan',
			':last_name' => 'ivanov',
			':password' => '12345',
		]);

		$connectionStub->method('prepare')->willReturn($statmentMock);

		$repository = new SqliteUsersRepository($connectionStub);

		$repository->save(
			new User(
				new UUID('0d5440ef-38d4-420b-bd1c-f882aeb18343'),
				'ivan123',
				'12345',
				new Name('ivan', 'ivanov')
			)
		);
	}
}