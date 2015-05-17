<?php

namespace Appizy\Command;

use Appizy\Tool;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ZipArchive;

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
            )->addOption(
                'keep-deflated',
                'kf',
                InputOption::VALUE_NONE,
                'Keep deflated spreadsheet files'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getArgument('path');

        if (!is_file($filePath)) {
            $output->writeln("File not found: $filePath. \n");
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

        $htmlTable = $elements['content'];

        // Import variables in local
        extract($elements, EXTR_OVERWRITE);

        // Start output buffering
        ob_start();

        // Include the template file
        include(__DIR__ . '/../webapp.tpl.php');

        // End buffering and return its contents
        $htmlTable = ob_get_clean();

        $filename = $fileDir . "/myappizy.html";
        $open = fopen($filename, "w");
        fwrite($open, $htmlTable);
        fclose($open);

        if (!$input->getOption('keep-deflated')) {
            // Removes temporary file
            self::delTree($extractDir);
            $output->writeln("Temporary files deleted");
        }

    }

    function delTree($dir)
    {
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::delTree("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}