<?php declare(strict_types=1);


namespace AP\Validator\Tests\Objects;

use AP\Validator\String\Length;

class Book
{
    public function __construct(
        #[Length(2, 200)]
        public string  $title,

        public Author  $author,

        #[Count(1, 10)]
        public array $authors,

        #[Length(2, 200)]
        public ?string $description = null,


        public ?Author $sub_author = null,

    )
    {
    }
}
