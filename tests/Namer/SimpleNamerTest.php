<?php

namespace Zenstruck\BackupBundle\Tests\Namer;

use Zenstruck\BackupBundle\Namer\SimpleNamer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class SimpleNamerTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $namer1 = new SimpleNamer();
        $namer2 = new SimpleNamer('foo');

        $this->assertSame(SimpleNamer::DEFAULT_NAME, $namer1->getName());
        $this->assertSame('foo', $namer2->getName());
    }
}
