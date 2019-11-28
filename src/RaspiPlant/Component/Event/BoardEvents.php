<?php

namespace RaspiPlant\Component\Event;

/**
 * Description of AbstractEvent
 *
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
final class BoardEvents implements ScriptEventInterface
{

    const BOARD_PRE_START = 'board.pre.start';
    const BOARD_POST_START = 'board.post.start';
    const BOARD_PRE_STOP = 'board.pre.stop';
    const BOARD_POST_STOP = 'board.post.stop';

    public static function getEvents()
    {
        return [
            self::BOARD_PRE_START,
            self::BOARD_POST_START,
            self::BOARD_POST_STOP,
            self::BOARD_PRE_STOP
        ];
    }
}
