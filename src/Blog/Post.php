<?php
namespace Tgu\Aksenov\Blog;

class Post
{
    public function __construct(
        private UUID $uuid,
        private UUID $userUuid,
        private string $title,
        private string $text,
    )
    {
    }

    public function __toString(): string
    {
        return $this->userUuid . ' пишет: ' . PHP_EOL . $this->title . PHP_EOL . $this->text;
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return User
     */
    public function getUserUuid(): UUID
    {
        return $this->userUuid;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }
}