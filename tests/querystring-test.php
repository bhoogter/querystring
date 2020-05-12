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
}