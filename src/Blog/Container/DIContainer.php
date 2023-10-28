<?php
namespace Tgu\Aksenov\Blog\Container;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use Tgu\Aksenov\Blog\Exceptions\NotFoundException;

class DIContainer implements ContainerInterface
{
	private array $resolves = [];

	public function has(string $type): bool
	{
		try {
			$this->get($type);
		} catch (NotFoundException) {
			return false;
		}

		return true;
	}

	public function bind(string $type, $class)
	{
		$this->resolves[$type] = $class;
	}

	public function get(string $type): object
	{
		if (array_key_exists($type, $this->resolves)) {
			$typeToCreate = $this->resolves[$type];

			if (is_object($typeToCreate)) {
				return $typeToCreate;
			}

			return $this->get($typeToCreate);
		}

		if (!class_exists($type)) {
			throw new NotFoundException("Cannot resolve type: $type");
		}

		$reflcetionClass = new ReflectionClass($type);

		$constructor = $reflcetionClass->getConstructor();

		if ($constructor === null) {
			return new $type();
		}

		$parameters = [];

		foreach ($constructor->getParameters() as $parameter) {
			$parameterType = $parameter->getType()->getName();

			$parameters[] = $this->get($parameterType);
		}
		
		return new $type(...$parameters);
	}
}