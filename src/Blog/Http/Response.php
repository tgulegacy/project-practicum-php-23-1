<?php
namespace Tgu\Aksenov\Blog\Http;

abstract class Response
{
	protected const SUCCEESS = true;

	public function send(): void
	{
		$data = ['succuess' => static::SUCCEESS] + $this->payload();
		
		header('Content-Type: application/json');

		echo json_encode($data, JSON_THROW_ON_ERROR);
	}

	abstract protected function payload(): array;
}