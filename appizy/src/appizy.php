<?php

$filePath = $argv[1];


if (!is_file($filePath)) {
    echo("File not found: $filePath. \n");
    throw new Exception();
}

$fileDir = dirname($filePath);
$extractDir = $fileDir . '/deflated';

$zip = new ZipArchive;
$zip->open($filePath);
$zip->extractTo($extractDir);
$zip->close();

$xml_path[] = $extractDir . "/styles.xml";
$xml_path[] = $extractDir . "/content.xml";

include('Tool.php');

$tool = new Tool(true);
$tool->tool_parse_wb($xml_path);
$tool->tool_clean();

$elements = $tool->tool_render(null, 1, array(
    'compact css'  => false,
    'jquery tab'   => false,
    /* 'freeze' => $option_freeze,*/
    'print header' => true,
));

$htmlTable = $elements['content'];

// Import variables in local

extract($elements, EXTR_OVERWRITE);

// Start output buffering
ob_start();

// Include the template file
include('webapp.tpl.php');

// End buffering and return its contents
$htmlTable = ob_get_clean();

$filename = $fileDir . "/myappizy.html";
$open = fopen($filename, "w");
fwrite($open, $htmlTable);
fclose($open);

// Removes temporary file
delTree($extractDir);


function delTree($dir)
{
    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }

    return rmdir($dir);
}
