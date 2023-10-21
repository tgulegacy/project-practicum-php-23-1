<?php

namespace Tgu\Aksenov\Blog\Http\Actions;

use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Http\Response;

interface ActionInterface
{
	public function handle(Request $request): Response;
}