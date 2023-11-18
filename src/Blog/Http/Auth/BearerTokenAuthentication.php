<?php

namespace Tgu\Aksenov\Blog\Http\Auth;

use DateTimeImmutable;
use Tgu\Aksenov\Blog\Exceptions\AuthException;
use Tgu\Aksenov\Blog\Exceptions\AuthTokenREpositoryException;
use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Http\Auth\TokenAuthenticationInterface;
use Tgu\Aksenov\Blog\Repositories\AuthTokenRepository\AuthTokenRepositoryInterface;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\Http\Request;

class BearerTokenAuthentication implements TokenAuthenticationInterface
{
	private const HEADER_PREFIX = 'Bearer ';

	public function __construct(
		private AuthTokenRepositoryInterface $authTokenRepository,
		private UserRepositoryInterface $userRepository
	)
	{
		
	}

	public function user(Request $request): User
	{
		try { 
			$header = $request->header('Authorization');
		} catch (HttpException $error) {
			throw new AuthException($error->getMessage());
		}

		if (!str_starts_with($header, self::HEADER_PREFIX)) {
			throw new AuthException("Malformed token: [$header]");
		}

		$token = mb_substr($header, strlen(self::HEADER_PREFIX));

		try {
			$authToken = $this->authTokenRepository->get($token);
		} catch (AuthTokenREpositoryException) {
			throw new AuthException("Bad token: [$token]");
		}

		if ($authToken->getExpiresOn() <= new DateTimeImmutable()) {
			throw new AuthException("Token expired: [$token]");
		}

		$userUuid = $authToken->getUuid();

		return $this->userRepository->get($userUuid);
	}
}