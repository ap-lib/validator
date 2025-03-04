<?php declare(strict_types=1);


use AP\ErrorNode\Errors;
use AP\Validator\Object\ObjectValidator;
use AP\Validator\Tests\Objects\SomeObject;
use PHPUnit\Framework\TestCase;

final class ObjectValidatorTest extends TestCase
{

    public function testBase(): void
    {
        $goodObject = new SomeObject(
            "yuri",
            "gagarin",
        );

        $this->assertTrue((new ObjectValidator)
            ->validate($goodObject)
        );
    }

    public function testError(): void
    {
        $goodObject = new SomeObject(
            "i", // too short name
            "am",
        );

        $res = (new ObjectValidator)
            ->validate($goodObject);

        $this->assertInstanceOf(
            Errors::class,
            $res
        );
    }
}
