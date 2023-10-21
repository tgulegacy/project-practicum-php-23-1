<?php
namespace Tgu\Aksenov\Blog\Http;

class ErrorResponse extends Response
{
	protected const SUCCEESS = false;

	public function __construct(
		private string $reason = 'Some goes wrong'
	)
	{
		
	}

	protected function payload(): array
	{
		return ['reason' => $this->reason];
	}
}