<?php

namespace Zenstruck\BackupBundle\Tests\Namer;

use Zenstruck\BackupBundle\Namer\TimestampNamer;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class TimestampNamerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getNameProvider
     */
    public function testGetName($format, $prefix)
    {
        $namer = new TimestampNamer($format, $prefix);
        $dateTime = new \DateTime();

        $this->assertSame($prefix.$dateTime->format($format), $namer->getName());
    }

    public function getNameProvider()
    {
        return array(
            array(TimestampNamer::DEFAULT_FORMAT, TimestampNamer::DEFAULT_PREFIX),
            array('d', null),
            array('dm', null),
            array('s', 'foo-')
        );
    }
}
