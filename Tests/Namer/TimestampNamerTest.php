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
    public function testGetName($format, $prefix, $timezone = null)
    {
        $namer = new TimestampNamer($format, $prefix, $timezone);
        $timezone = $timezone ? new \DateTimeZone($timezone) : null;
        $dateTime = new \DateTime('now', $timezone);

        $this->assertSame($prefix.$dateTime->format($format), $namer->getName());
    }

    public function getNameProvider()
    {
        return array(
            array(TimestampNamer::DEFAULT_FORMAT, TimestampNamer::DEFAULT_PREFIX),
            array(TimestampNamer::DEFAULT_FORMAT, TimestampNamer::DEFAULT_PREFIX, 'UTC'),
            array('d', null),
            array('dm', null),
            array('s', 'foo-'),
        );
    }
}
