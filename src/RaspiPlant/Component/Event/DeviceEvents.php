<?php

namespace RaspiPlant\Component\Event;

/**
 * Description of DeviceEvents
 *
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
final class DeviceEvents {

    const DEVICE_PRE_START = 'device.pre.start';
    const DEVICE_POST_START = 'device.post.start';

    const DEVICE_PRE_CALL = 'device.pre.call';
    const DEVICE_POST_CALL = 'device.post.call';

    const DEVICE_PRE_STOP = 'device.pre.stop';
    const DEVICE_POST_STOP = 'device.post.stop';

}
