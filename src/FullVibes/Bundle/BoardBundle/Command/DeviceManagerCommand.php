<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

class DeviceManagerCommand extends ContainerAwareCommand
{

    protected $container;

    protected $deviceManager;

    protected $questionHelper;

    protected $input;

    protected $output;

    protected function configure() {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('raspiplant:devices:manage')
            // the short description shown while running "php bin/console list"
            ->setDescription('Manage devices.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to create or edit a device...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $this->questionHelper = $this->getHelper('question');
        $this->input = $input;
        $this->output = $output;



        $question = new ChoiceQuestion(
            'What do you want to do ',
            array('create' => 'Create a device', 'edit' => 'Edit a device'),
            1
        );

        $question->setErrorMessage('Action %s is invalid.');

        $action = $this->questionHelper->ask($input, $output, $question);
        $output->writeln('You have just selected: '.$action);


        switch ($action) {
            default:
            case "create" :
                $this->createDevice();
                return;
            break;
            case "edit" :
                $this->editDevice();
                return;
            break;
        }





    }

    protected function createDevice() {

    }

    protected function editDevice() {

    }

}
