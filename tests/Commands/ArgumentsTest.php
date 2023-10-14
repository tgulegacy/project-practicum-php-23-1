<?php
namespace Tgu\Aksenov\Blog\UnitTests\Commands;

use PHPUnit\Framework\TestCase;
use Tgu\Aksenov\Blog\Commands\Arguments;
use Tgu\Aksenov\Blog\Exceptions\ArgumentsException;

class ArgumentsTest extends TestCase
{
	static public function argumentsProvider(): iterable
	{
		return [
			['some_string', 'some_string'],
			[' some_string', 'some_string'],
			[' some_string ', 'some_string'],
			[123, '123'],
			[12.3, '12.3'],
		];
	}

	public function testItThrowsAnExceptionWhenArgumentIsAbsent(): void
	{
		//Arrange - подготовка
		$arguments = new Arguments([]);

		//Assert - проверка
		$this->expectException(ArgumentsException::class);
		$this->expectExceptionMessage("No such argument: some_key");

		//Act - действие
		$arguments->get('some_key');
	}

	/**
	 * @dataProvider argumentsProvider
	 */
	public function testItConvertArgumentsToStrings($inputValue, $expectedValue): void
	{
		//Arrange - подготовка
		$arguments = new Arguments(['some_key' => $inputValue]);

		//Act - действие
		$value = $arguments->get('some_key');

		//Assert - проверка
		$this->assertSame($expectedValue, $value);
	}
}