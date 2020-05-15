<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class pages_test extends TestCase
{
    public function testCreatePagesClass(): void {
        $s = "a=m&b=3";
        $this->assertEquals("?$s", querystring::aqm($s));
        $this->assertEquals("?$s", querystring::aqm("?$s"));
        $this->assertEquals("$s", querystring::rqm("?$s"));
        $this->assertEquals("$s", querystring::rqm("$s"));
    }

    public function testPParse(): void {
        $s = "a=m&b=3&f=4444&a=mm&a=mmm";
        $result = querystring::pparse($s);
        $this->assertEquals(4444, $result['f']);
        $this->assertEquals(3, count($result['a']));
    }

    public function testAccess(): void {
        $s = "?a=m&b=3&f=4444&a=mm&a=mmm";
        $this->assertEquals("$s&z=y", querystring::add($s, "z", "y"));
        $this->assertEquals("3", querystring::get($s, "b"));
        $this->assertEquals("?a=m&f=4444&a=mm&a=mmm", querystring::del($s, "b"));
    }

    public function testOverwrite(): void {
        $s = "?z=1";
        $this->assertEquals("?z=2", querystring::add($s, "z", "2"));
    }

    public function testDualOverwrite(): void {
        $s = "?a=1&b=2";
        $this->assertEquals("?b=2&a=4", querystring::add($s, "a", "4"));
        $this->assertEquals("?a=1&b=5", querystring::add($s, "b", "5"));
    }
}
