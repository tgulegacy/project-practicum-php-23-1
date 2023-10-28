<?php
namespace Tgu\Aksenov\Blog\UnitTests\Container;

use PHPUnit\Framework\TestCase;
use Tgu\Aksenov\Blog\Container\DIContainer;
use Tgu\Aksenov\Blog\Exceptions\NotFoundException;
use Tgu\Aksenov\Blog\Repositories\UserRepository\InMemoryUserRepository;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;

class DIContainerTest extends TestCase
{
	public function testItThrowsAnExceptionIfCannotResolveType(): void
	{
		$container = new DIContainer();

		$this->expectException(NotFoundException::class);
		$this->expectExceptionMessage('Cannot resolve type: Tgu\Aksenov\Blog\UnitTests\Container\SomeClass');

		$container->get(SomeClass::class);
	}

	public function testItResolvesClassWithoutDependencies(): void
	{
		$container = new DIContainer();

		$object = $container->get(SomeClassWithoutDependencies::class);

		$this->assertInstanceOf(SomeClassWithoutDependencies::class, $object);
	}

	public function testItResolvesClassByContract(): void
	{
		$container = new DIContainer;

		$container->bind(UserRepositoryInterface::class, InMemoryUserRepository::class);

		$object = $container->get(UserRepositoryInterface::class);

		$this->assertInstanceOf(InMemoryUserRepository::class, $object);
	}

	public function testItReturnPredefinedObject(): void
	{
		$container = new DIContainer();

		$container->bind(SomeClassWithParameter::class, new SomeClassWithParameter(123));

		$object = $container->get(SomeClassWithParameter::class);

		$this->assertInstanceOf(SomeClassWithParameter::class, $object);

		$this->assertSame(123, $object->getValue());
	}

	public function testItResolvesClassWithDependencies(): void
	{
		$container = new DIContainer;

		$container->bind(SomeClassWithParameter::class, new SomeClassWithParameter(123));

		$object = $container->get(ClassDependingOnAnother::class);

		$this->assertInstanceOf(ClassDependingOnAnother::class, $object);
	}
}