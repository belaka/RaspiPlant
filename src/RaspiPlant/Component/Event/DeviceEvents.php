<?php

namespace RaspiPlant\Component\Event;

/**
 * Description of DeviceEvents
 *
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
final class DeviceEvents implements ScriptEventInterface
{

    const DEVICE_PRE_START = 'device.pre.start';
    const DEVICE_POST_START = 'device.post.start';

    const DEVICE_PRE_CALL = 'device.pre.call';
    const DEVICE_POST_CALL = 'device.post.call';

    const DEVICE_PRE_STOP = 'device.pre.stop';
    const DEVICE_POST_STOP = 'device.post.stop';

    public static function getEvents()
    {
        return [
            self::DEVICE_PRE_START,
            self::DEVICE_POST_START,
            self::DEVICE_PRE_CALL,
            self::DEVICE_POST_CALL,
            self::DEVICE_PRE_STOP,
            self::DEVICE_POST_STOP
        ];
    }

}
