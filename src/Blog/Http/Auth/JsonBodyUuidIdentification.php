<?php

namespace Tgu\Aksenov\Blog\Http\Auth;

use InvalidArgumentException;
use Tgu\Aksenov\Blog\Exceptions\AuthException;
use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Http\Auth\IdentificationInterface;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

class JsonBodyUuidIdentification implements IdentificationInterface
{
	public function __construct(
		private UserRepositoryInterface $userRepository
	)
	{
		
	}

	public function user(Request $request): User
	{
		try {
			$username = $request->jsonBodyField('username');
		} catch (HttpException|InvalidArgumentException $error) {
			throw new AuthException($error->getMessage());
		}

		try {
			return $this->userRepository->getByUsername($username);
		} catch (UserNotFoundException $error) {
			throw new AuthException($error->getMessage());
		}
	}
}