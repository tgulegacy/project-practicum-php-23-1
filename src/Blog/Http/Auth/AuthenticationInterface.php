<?php

namespace Tgu\Aksenov\Blog\Http\Auth;

use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\User;

interface AuthenticationInterface
{
	public function user(Request $request): User;
}