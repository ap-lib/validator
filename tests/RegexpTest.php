<?php declare(strict_types=1);


use AP\Validator\String\IP;
use AP\Validator\String\IPGlobalRange;
use PHPUnit\Framework\TestCase;

final class RegexpTest extends TestCase
{
    public function testGlobalRe(): void
    {
        $options1 = 0;
        $options2 = FILTER_FLAG_GLOBAL_RANGE;

        $this->assertEquals(
            $options1 | FILTER_FLAG_GLOBAL_RANGE,
            $options2 | FILTER_FLAG_GLOBAL_RANGE,
        );
    }

    public function testGlobal(): void
    {
        $ipv4_local = "127.0.0.1";
        $ipv6_local = "::1";

        $ipv4_global = "12.0.0.1";
        $ipv6_global = "2600:8807:cd2:5b00:4072:8957:a025:c3de";

        $this->assertTrue((new IPGlobalRange())->validateString($ipv4_global));
        $this->assertTrue((new IPGlobalRange())->validateString($ipv6_global));

        $this->assertNotTrue((new IPGlobalRange())->validateString($ipv4_local));
        $this->assertNotTrue((new IPGlobalRange())->validateString($ipv6_local));

        // over
        $this->assertTrue((new IPGlobalRange(options: FILTER_FLAG_GLOBAL_RANGE))->validateString($ipv4_global));
        $this->assertTrue((new IPGlobalRange(options: FILTER_FLAG_GLOBAL_RANGE))->validateString($ipv6_global));
        $this->assertNotTrue((new IPGlobalRange(options: FILTER_FLAG_GLOBAL_RANGE))->validateString($ipv4_local));
        $this->assertNotTrue((new IPGlobalRange(options: FILTER_FLAG_GLOBAL_RANGE))->validateString($ipv6_local));
    }


}
