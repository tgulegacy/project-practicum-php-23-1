<?php

namespace Tgu\Aksenov\Blog\Http\Actions\Auth;

use DateTimeImmutable;
use Tgu\Aksenov\Blog\AuthToken;
use Tgu\Aksenov\Blog\Exceptions\AuthException;
use Tgu\Aksenov\Blog\Http\Actions\ActionInterface;
use Tgu\Aksenov\Blog\Http\Auth\PasswordAuthenticationInterface;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Http\Response;
use Tgu\Aksenov\Blog\Http\SuccessfulResponse;
use Tgu\Aksenov\Blog\Repositories\AuthTokenRepository\AuthTokenRepositoryInterface;

class Login implements ActionInterface
{
	public function __construct(
		private PasswordAuthenticationInterface $passwordAuthentication,
		private AuthTokenRepositoryInterface $authTokenRepository
	)
	{
	}

	public function handle(Request $request): Response
	{
		try {
			$user = $this->passwordAuthentication->user($request);
		} catch (AuthException $error) {
			return new ErrorResponse($error->getMessage());
		}

		$authToken = new AuthToken(
			bin2hex(random_bytes(40)),
			$user->getUuid(),
			(new DateTimeImmutable())->modify('+1 day')
		);

		$this->authTokenRepository->save($authToken);

		return new SuccessfulResponse([
			'token' => (string)$authToken,
		]);
	}
}