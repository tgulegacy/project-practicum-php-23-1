<?php

namespace Tgu\Aksenov\Blog\UnitTests\Commands;

use PHPUnit\Framework\TestCase;
use Tgu\Aksenov\Blog\Commands\Arguments;
use Tgu\Aksenov\Blog\Commands\CreateUserCommand;
use Tgu\Aksenov\Blog\Exceptions\ArgumentsException;
use Tgu\Aksenov\Blog\Exceptions\CommandException;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Repositories\UserRepository\DummyUsersRepository;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Tgu\Aksenov\Blog\UnitTests\DummyLogger;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

class CreateUserCommandTest extends TestCase
{
	private function makeUserRepository(): UserRepositoryInterface
	{
		return new class implements UserRepositoryInterface {
			public function save(User $user): void
			{
				// ничего
			}

			public function get(UUID $uuid): User
			{
				throw new UserNotFoundException("Not found");
			}

			public function getByUsername(string $username): User
			{
				throw new UserNotFoundException("Not found");
			}
		};
	}

	public function testItThrowsAnExceptionWhenUserAlreadyExists(): void
	{
		$command = new CreateUserCommand(
			new DummyUsersRepository(),
			new DummyLogger
		);

		$this->expectException(CommandException::class);
		$this->expectExceptionMessage('User already exists: Ivan');

		$command->handle(new Arguments(['username' => 'Ivan', 'password' => '12345']));
	}

	public function testItRequiresFirstName(): void
	{
		$command = new CreateUserCommand(
			$this->makeUserRepository(),
			new DummyLogger
		);

		$this->expectException(ArgumentsException::class);
		$this->expectExceptionMessage('No such argument: first_name');

		$command->handle(new Arguments(['username' => 'Ivan', 'password' => '12345']));
	}

	public function testItRequiresLastName(): void
	{
		$command = new CreateUserCommand(
			$this->makeUserRepository(),
			new DummyLogger
		);

		$this->expectException(ArgumentsException::class);
		$this->expectExceptionMessage('No such argument: last_name');

		$command->handle(new Arguments(['username' => 'Ivan', 'first_name' => 'Ivan', 'password' => '12345']));
	}

	public function testItSavesUserToRepository(): void
	{
		$usersRepository = new class implements UserRepositoryInterface {
			private bool $called = false;

			public function save(User $user): void
			{
				$this->called = true;
			}

			public function get(UUID $uuid): User
			{
				throw new UserNotFoundException("Not found");
			}

			public function getByUsername(string $username): User
			{
				throw new UserNotFoundException("Not found");
			}

			public function wasCalled(): bool
			{
				return $this->called;
			}
		};

		$command = new CreateUserCommand(
			$usersRepository,
			new DummyLogger
		);

		$command->handle(new Arguments([
			'username' => 'Ivan', 
			'first_name' => 'Ivan',
			'last_name' => 'Ivanov',
			'password' => '12345'
		]));

		$this->assertTrue($usersRepository->wasCalled());
	}

	public function testItPequiewsPassword(): void
	{
		$command = new CreateUserCommand(
			$this->makeUserRepository(),
			new DummyLogger
		);

		$this->expectException(ArgumentsException::class);
		$this->expectExceptionMessage('No such argument: password');

		$command->handle(new Arguments(['username' => 'Ivan']));
	}
}