<?php

namespace Tgu\Aksenov\Blog\UnitTests\Http\Actions\Users;

use PHPUnit\Framework\TestCase;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Http\Actions\Users\FindByUsername;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Http\SuccessfulResponse;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Tgu\Aksenov\Blog\Person\Name;
use Tgu\Aksenov\Blog\UnitTests\DummyLogger;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

class FindByUsernameTest extends TestCase
{
	private function uesrsRepository(array $users): UserRepositoryInterface
	{
		return new class($users) implements UserRepositoryInterface
		{
			public function __construct(
				private array $users
			)
			{
				
			}

			public function save(User $user): void
			{
				
			}

			public function get(UUID $uuid): User
			{
				throw new UserNotFoundException("Not found");
			}

			public function getByUsername(string $username): User
			{
				foreach ($this->users as $user) {
					if ($user instanceof User && $username === $user->getUsername()) {
						return $user;
					}

					throw new UserNotFoundException("Not found");
				}
			}
		};
	}
	
	/**
   * @runInSeparateProcess
   * @preserveGlobalState disable
	 * @throws JsonException
   */
	public function testItReturnErrorResponseIfNoUsernameProvided(): void
	{
		$request = new Request([], [], '');

		$userRepsitory = $this->uesrsRepository([]);

		$action = new FindByUsername($userRepsitory, new DummyLogger);

		$response = $action->handle($request);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->expectOutputString('{"succuess":false,"reason":"No such query param in the request: username"}');

		$response->send();
	}

	/**
   * @runInSeparateProcess
   * @preserveGlobalState disable
	 * @throws JsonException
   */
	public function testItReturnErrorResponseIfUserNotFound(): void
	{
		$request = new Request(['username' => 'ivan'], [], '');

		$userRepsitory = $this->uesrsRepository([
			new User(
				UUID::random(),
				'ivan123',
				'12345',
				new Name('Ivan', 'Ivanov'),	
			)
		]);

		$action = new FindByUsername($userRepsitory, new DummyLogger);

		$response = $action->handle($request);

		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->expectOutputString('{"succuess":false,"reason":"Not found"}');

		$response->send();
	}

	/**
   * @runInSeparateProcess
   * @preserveGlobalState disable
	 * @throws JsonException
   */
	public function testItReturnSuccessfulResponse(): void
	{
		$request = new Request(['username' => 'ivan'], [], '');

		$userRepsitory = $this->uesrsRepository([
			new User(
				UUID::random(),
				'ivan',
				'12345',
				new Name('Ivan', 'Ivanov'),	
			)
		]);

		$action = new FindByUsername($userRepsitory, new DummyLogger);

		$response = $action->handle($request);

		$this->assertInstanceOf(SuccessfulResponse::class, $response);
		$this->expectOutputString('{"succuess":true,"data":{"username":"ivan","name":"Ivan Ivanov"}}');

		$response->send();
	}
}