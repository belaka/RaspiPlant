<?php

namespace RaspiPlant\Bundle\BoardBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use RaspiPlant\Bundle\BoardBundle\Entity\Board;

class BoardFixtures extends Fixture
{
    public const BOARD_REFERENCE = 'TestBoard';

    public function load(ObjectManager $manager)
    {
        $board = new Board();
        $board->setName(self::BOARD_REFERENCE);
        $board->setActive(true);

        $manager->persist($board);

        $manager->flush();

        $this->addReference(self::BOARD_REFERENCE, $board);
    }
}
