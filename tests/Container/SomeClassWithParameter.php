<?php
namespace Tgu\Aksenov\Blog\UnitTests\Container;

class SomeClassWithParameter
{
	public function __construct(
		private int $value
	)
	{
		
	}

	public function getValue(): int
	{
		return $this->value;
	}
}