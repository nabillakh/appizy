<?php

namespace Appizy\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;
use Appizy\Tool;

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

        $filePath = $path;

        if (!is_file($filePath)) {
            echo("File not found: $filePath. \n");
            throw new Exception();
        }

        try {
            echo "Decompressing file \n";

            $fileDir = dirname($filePath);
            $extractDir = $fileDir . '/deflated';

            $zip = new ZipArchive;
            $zip->open($filePath);
            $zip->extractTo($extractDir);
            $zip->close();

        } catch (Exception $e) {
            echo 'Error while file decompression: ' . $e->getMessage() . "\n";
        }

        $xml_path[] = $extractDir . "/styles.xml";
        $xml_path[] = $extractDir . "/content.xml";



        $tool = new Tool(true);

        try {
            echo "Parsing spreadsheet \n";
            $tool->tool_parse_wb($xml_path);
        } catch (Exception $e) {
            echo 'Error while parsing spreadsheet: ' . $e->getMessage() . "\n";
        }

        try {
            echo "Rendering application \n";
            $tool->tool_clean();

            $elements = $tool->tool_render(null, 1, array(
                'compact css'  => false,
                'jquery tab'   => false,
                /* 'freeze' => $option_freeze,*/
                'print header' => true,
            ));
        } catch (Exception $e) {
            echo 'Error while rendering the webapplication: ' . $e->getMessage() . "\n";
        }
    }
}