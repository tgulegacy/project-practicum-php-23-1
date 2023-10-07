<?php
namespace Tgu\Aksenov\Blog\Person;

use DateTimeImmutable;

class Person {
	public function __construct(
		private Name $name,
		private DateTimeImmutable $regiseredOn
	)
	{}

	public function __toString()
		{
			return $this->name . ' (на сайте с ' . $this->regiseredOn->format('Y-m-d') . ')';
		}
}