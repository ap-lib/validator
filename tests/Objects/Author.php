<?php declare(strict_types=1);


namespace AP\Validator\Tests\Objects;

use AP\Validator\String\Length;

class Author
{
    public function __construct(
        #[Length(2, 200)]
        public string $name,
    )
    {
    }
}
