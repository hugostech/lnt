<?php

namespace Tests\Feature;

use App\Lib\BuningsSpider;
use App\Lib\Mitro10Spider;
use App\Lib\TheToolsShedSpider;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SpiderTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testMitre10Spider(){
        $spider = new Mitro10Spider('https://www.mitre10.co.nz/shop/dewalt-battery-18-volt/p/244698');
        $content = $spider->handle();
        $this->assertIsFloat($content);
    }

    public function testToolShedSpider(){
        $spider = new TheToolsShedSpider('https://www.thetoolshed.co.nz/product/13373-hikoki-angle-grinder-100mm-730w-with-case');
        $content = $spider->handle();
        $this->assertIsFloat($content);
    }

    public function testBunningsSpider(){
        $spider = new BuningsSpider('https://www.bunnings.co.nz/marquee-3-piece-bayfield-wicker-corner-sofa_p0177356');
        $content = $spider->handle();
        $this->assertIsFloat($content);
    }
}
