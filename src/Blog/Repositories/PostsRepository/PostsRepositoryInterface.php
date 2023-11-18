<?php
namespace Tgu\Aksenov\Blog\Repositories\PostsRepository;

use Tgu\Aksenov\Blog\Post;

interface PostsRepositoryInterface
{
    public function save(Post $post): void;
//    public function getByTitle(string $title): Post;
//    public function getByUuid(UUID $uuid): Post;
//    public function delete(UUID $uuid): void;
}