<?php

namespace Tgu\Aksenov\Blog\Http\Auth;

use Tgu\Aksenov\Blog\Exceptions\AuthException;
use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Exceptions\InvalidArgumentException;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Tgu\Aksenov\Blog\User;

class PasswordAuthentication implements PasswordAuthenticationInterface
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
			$user = $this->userRepository->getByUsername($username);
		} catch (UserNotFoundException $error) {
			throw new AuthException($error->getMessage());
		}

		try {
			$password = $request->jsonBodyField('password');
		} catch (HttpException $error) {
			throw new AuthException($error->getMessage());
		}

		$hash = hash('sha256', $password);

		if (!$user->chackPassword($password)) {
			throw new AuthException('Wrong password');
		}

		return $user;
	}
}