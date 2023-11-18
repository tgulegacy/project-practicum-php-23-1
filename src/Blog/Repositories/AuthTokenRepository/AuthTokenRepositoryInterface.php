<?php

namespace Tgu\Aksenov\Blog\Repositories\AuthTokenRepository;

use Tgu\Aksenov\Blog\AuthToken;

interface AuthTokenRepositoryInterface
{
	public function save(AuthToken $authToken): void;

	public function get(string $token): AuthToken;
}