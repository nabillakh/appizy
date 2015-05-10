<?php

namespace Appizy\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConvertCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('appizy')
            ->setDescription('Convert a spreadsheet to webcontent')
            ->addArgument(
                'path',
                InputArgument::OPTIONAL,
                'Which file do you want to convert?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Starting Appizy");

        $path = $input->getArgument('path');
        if (file_exists($path)) {
            $text = "I found your spreadsheet";
        } else {
            $text = "Not file found";
        }

        $output->writeln($text);
    }
}