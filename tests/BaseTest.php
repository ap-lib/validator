<?php declare(strict_types=1);

namespace AP\Validator\Tests;

use AP\ErrorNode\Errors;
use AP\Validator\String\Length;
use PHPUnit\Framework\TestCase;

final class BaseTest extends TestCase
{

    public function testGood(): void
    {
        $value = "hello";

        $validator = new Length(5, 10);

        $this->assertTrue(
            $validator->validate($value)
        );
    }

    public function testError(): void
    {
        $value = "hello world";

        $validator = new Length(5, 10);
        $res       = $validator->validate($value);

        $this->assertInstanceOf(
            Errors::class,
            $res
        );
    }
}
