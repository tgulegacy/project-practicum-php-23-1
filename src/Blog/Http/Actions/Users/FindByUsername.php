<?php

namespace Tgu\Aksenov\Blog\Http\Actions\Users;

use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Exceptions\UserNotFoundException;
use Tgu\Aksenov\Blog\Http\Actions\ActionInterface;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Http\Response;
use Tgu\Aksenov\Blog\Http\SuccessfulResponse;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;

class FindByUsername implements ActionInterface
{
	public function __construct(
		private UserRepositoryInterface $userRepository
	)
	{
		
	}

	public function handle(Request $request): Response
	{
		try {
			$username = $request->query('username');
		} catch (HttpException $error) {
			return new ErrorResponse($error->getMessage());
		}

		try {
			$user = $this->userRepository->getByUsername($username);
		} catch (UserNotFoundException $error) {
			return new ErrorResponse($error->getMessage());
		}

		return new SuccessfulResponse([
			'username' => $user->getUsername(),
			'name' => (string)$user->getName(),
		]);
	}
}