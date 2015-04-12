<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
disable_ob();
chdir(dirname(__DIR__));

header("Content-type: application/octet-stream");
// Need to output 2K text: http://stackoverflow.com/questions/7740646/jquery-ajax-read-the-stream-incrementally
// echo str_repeat('°°filler°°', 205) . PHP_EOL;

print ("<br>### <b>Starting Appizy</b><br/> \n");

$appDir = $_POST['app_id'] ? 'tmp/' . $_POST['app_id'] . '/' : 'tmp';
$sourceFile = $_POST['filename'];

$command = "cd ../.. & ";
$command .= "php -f src/Appizy/appizy.php 'public/" . $appDir . $sourceFile . "'";

passthru($command, $status);

if ($status !== 0) {

    print (
    '<b class="text-danger">### Error while running Appizy</b> .'
    );

} else {
    // Delete source file
    unlink("public/$appDir$sourceFile");

    // Create an archive containing the generated files
    $zip = new ZipArchive();
    $pathzip = __DIR__ . '/' . $appDir . "myappi.zip";

    // Initialize archive object
    $zip = new ZipArchive;
    $zip->open($pathzip, ZipArchive::CREATE);

    // Initialize empty "delete list"
    $filesToDelete = array();

    // Create recursive directory iterator
    $files = array_diff(scandir( __DIR__ . '/'.$appDir), array('.','..'));

    foreach ($files as $file) {
       $zip->addFile(__DIR__ . '/'.$appDir . '/'.$file);
    }

    // Zip archive will be created only after closing object
    $zip->close();

    foreach ($files as $file) {
        unlink(__DIR__ . '/'.$appDir . '/'.$file);
    }

    print ("<b>### Appizy done!</b>");
}

function disable_ob()
{
    // Turn off output buffering
    ini_set('output_buffering', 'off');
    // Turn off PHP output compression
    ini_set('zlib.output_compression', false);
    // Implicitly flush the buffer(s)
    ini_set('implicit_flush', true);
    ob_implicit_flush(true);
    // Turn off output buffering
    while (@ob_end_flush()) {
        ;
    }
    // Disable apache output buffering/compression
    if (function_exists('apache_setenv')) {
        apache_setenv('no-gzip', '1');
        apache_setenv('dont-vary', '1');
    }
}