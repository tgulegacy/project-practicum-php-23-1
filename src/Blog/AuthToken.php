<?php

namespace Tgu\Aksenov\Blog;

use DateTimeImmutable;

class AuthToken
{
	public function __construct(
		private string $token,
		private UUID $uuid,
		private DateTimeImmutable $expiresOn
	)
	{
		
	}

	public function getToken(): string
	{
		return $this->token;
	}

	public function getUuid(): UUID
	{
		return $this->uuid;
	}

	public function getExpiresOn(): DateTimeImmutable
	{
		return $this->expiresOn;
	}

	public function __toString(): string
	{
		return $this->token;
	}
}