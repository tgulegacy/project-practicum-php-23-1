<?php

namespace Tgu\Aksenov\Blog\Http\Actions\Users;

use Psr\Log\LoggerInterface;
use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Http\Actions\ActionInterface;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Http\Response;
use Tgu\Aksenov\Blog\Http\SuccessfulResponse;
use Tgu\Aksenov\Blog\Person\Name;
use Tgu\Aksenov\Blog\Repositories\UserRepository\UserRepositoryInterface;
use Tgu\Aksenov\Blog\User;
use Tgu\Aksenov\Blog\UUID;

class CreateUser implements ActionInterface
{
  public function __construct(
    private UserRepositoryInterface $usersRepository,
    private LoggerInterface $logger
  )
  {
  }

  public function handle(Request $request): Response
  {
    $newUserUuid = UUID::random();

    try {
      $user = new User(
        $newUserUuid,
				$request->jsonBodyField('username'),
        new Name(
          $request->jsonBodyField('first_name'),
          $request->jsonBodyField('last_name'),
        ),
      );
    } catch (HttpException $exception) {
      return new ErrorResponse($exception->getMessage());
    }

    $this->usersRepository->save($user);

    $this->logger->info("User created: $newUserUuid");

    return new SuccessfulResponse([
      'uuid' => (string)$newUserUuid
    ]);
  }
}