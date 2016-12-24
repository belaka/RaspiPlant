<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class BoardManagerCommand extends ContainerAwareCommand
{

    protected $container;

    protected $boardManager;

    protected $questionHelper;

    protected $input;

    protected $output;

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('raspiplant:boards:manage')
                // the short description shown while running "php bin/console list"
                ->setDescription('Manage boards.')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp("This command allows you to create or edit a board...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->questionHelper = $this->getHelper('question');
        $this->input = $input;
        $this->output = $output;



        $question = new ChoiceQuestion(
            'What do you want to do ',
            array('create' => 'Create a board', 'edit' => 'Edit a board'),
            1
        );

        $question->setErrorMessage('Action %s is invalid.');

        $action = $this->questionHelper->ask($input, $output, $question);
        $output->writeln('You have just selected: '.$action);


        switch ($action) {
            default:
            case "create" :
                $this->createBoard();
                return;
            break;
            case "edit" :
                $this->editBoard();
                return;
            break;
        }





    }

    protected function createBoard() {

    }

    protected function editBoard() {

        $boards = array();
        $this->container = $this->getContainer();
        $this->boardManager = $this->container->get("board.manager.board_manager");
        $boards = $this->boardManager->findAll();

        $question = new ChoiceQuestion(
            'Which board do you want to edit ',
            $boards,
            0
        );

        $question->setErrorMessage('Board %s is invalid.');

        $board = $this->questionHelper->ask($this->input, $this->output, $question);

        dump($boards[0]);
    }

}
