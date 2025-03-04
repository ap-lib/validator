<?php declare(strict_types=1);

namespace AP\Validator\Tests\Objects;

use AP\Validator\String\Length;

class SomeObject
{
    public function __construct(
        #[Length(2, 200)]
        public string $first_name,

        #[Length(2, 200)]
        public string $last_name,

//        #[Between(18, 99)]
//        public int    $age,
    )
    {
    }
}
