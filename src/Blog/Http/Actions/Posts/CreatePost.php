<?php

namespace Tgu\Aksenov\Blog\Http\Actions\Posts;

use Tgu\Aksenov\Blog\Exceptions\AuthException;
use Tgu\Aksenov\Blog\Exceptions\HttpException;
use Tgu\Aksenov\Blog\Http\Actions\ActionInterface;
use Tgu\Aksenov\Blog\Http\Auth\TokenAuthenticationInterface;
use Tgu\Aksenov\Blog\Http\ErrorResponse;
use Tgu\Aksenov\Blog\Http\Request;
use Tgu\Aksenov\Blog\Http\Response;
use Tgu\Aksenov\Blog\Http\SuccessfulResponse;
use Tgu\Aksenov\Blog\Post;
use Tgu\Aksenov\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use Tgu\Aksenov\Blog\UUID;

class CreatePost implements ActionInterface
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private TokenAuthenticationInterface $authentication
    )
    {
    }

    public function handle(Request $request): Response
	{
        try {
            $user = $this->authentication->user($request);
        } catch (AuthException $error) {
            return new ErrorResponse($error->getMessage());
        }

        $newPostUuid = UUID::random();

        try {
            $post = new Post(
                $newPostUuid,
                $user->getUuid(),
                $request->jsonBodyField('title'),
                $request->jsonBodyField('text')
            );
        } catch (HttpException $exception) {
            return new ErrorResponse($exception->getMessage());
        }

        $this->postsRepository->save($post);

        return new SuccessfulResponse([
            'uuid' => (string)$newPostUuid,
        ]);
    }
}