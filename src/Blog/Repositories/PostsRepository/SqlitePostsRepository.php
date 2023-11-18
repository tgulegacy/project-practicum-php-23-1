<?php

namespace Tgu\Aksenov\Blog\Repositories\PostsRepository;

use Tgu\Aksenov\Blog\Post;

class SqlitePostsRepository implements PostsRepositoryInterface
{
    public function __construct(
        private readonly \PDO $connection
    )
    {
    }

    public function save(Post $post): void
    {
        $statment = $this->connection->prepare(
            'INSERT INTO posts (uuid, user_uuid, title, text) VALUES (:uuid, :user_uuid, :title, :text)'
        );

        $statment->execute([
            ':uuid' => (string)$post->getUuid(),
            ':user_uuid' => (string)$post->getUserUuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText(),
        ]);
    }
}