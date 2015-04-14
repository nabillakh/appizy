<?php

$filePath = $argv[1];


if (!is_file($filePath)) {
    echo ("File not found: $filePath. \n");
    throw new Exception();
}

$fileDir = dirname($filePath);
$extractDir = $fileDir . '/deflated';

$zip = new ZipArchive;
$zip->open($filePath);
$zip->extractTo($extractDir);
$zip->close();

$xml_path[] = $extractDir."/styles.xml";
$xml_path[] = $extractDir."/content.xml";

include('service/Tool.class.php');

$tool = new Tool(true);
$tool->tool_parse_wb($xml_path);
$tool->tool_clean();

$elements = $tool->tool_render(NULL, 1, array(
    'compact css' => FALSE,
    'jquery tab' => FALSE,
    /* 'freeze' => $option_freeze,*/
    'print header' => TRUE,
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


function delTree($dir) {
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

function form_appizy_ui_submit($web_app_dir, $ss_name)
{

    $app_id = appizy_id_gen();
    $app_folder = $web_app_dir;
    $app_uri = $app_folder . '/myappizy.php';
    $file_path = $web_app_dir . $ss_name;
    // $app_prev_uri = $app_folder.'/myappizy-prev.php';
    // $app_prev_script_uri = $app_folder.'/script.js';
    // $app_onefile = $app_folder.'/one.html';
    // $app_url = file_create_url($app_uri);

    if (TRUE) {

        include('includes/spreadsheet.inc');

        // 1. Converts XLSX spreadsheet in ODS
        $workbook_path_parts = pathinfo($file_path);
        $workbook_ext = $workbook_path_parts['extension'];
        $workbook_name = $workbook_path_parts['filename'];

        if ($workbook_ext == 'xlsx' || $workbook_ext == 'xls') {
            // If XLSX, converts spreadsheet to ods and return new URI
            $file_path = appizy_ss_convert($workbook_name, $workbook_ext, $app_folder);

        }

        // 2. Deflates Workbook
        $xml_content = appizy_ss_extract($file_path, $app_folder . '/deflated');


        // 3. Parses the XML content
        include('service/Tool.class.php');

        $tool = new Tool($display_mess);
        $tool->tool_parse_wb($xml_content);


        // 4. Deletes deflated folder

        // 5.Renders
        $tool->tool_clean();

        $elements = $tool->tool_render(NULL, 1, array(
            'compact css'  => $option_csscompact,
            'jquery tab'   => $option_jquery_tab,
            /* 'freeze' => $option_freeze,*/
            'print header' => TRUE,
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

        $filename = $web_app_dir . "myappizy.html";
        $open = fopen($filename, "w");
        fwrite($open, $htmlTable);
        fclose($open);

        // cr�ation du zip (pour forcer t�l�chargement)
        $zip = new ZipArchive();
        $pathzip = $web_app_dir . "myappi.zip";

        if ($zip->open($pathzip, ZIPARCHIVE::CREATE) !== TRUE) {
            // exit("Impossible d'ouvrir <$pathzip>\n");
        } else {
            $zip->addFile($filename, 'appizy/index.html');
            //echo "Nombre de fichiers : " . $zip->numFiles . "\n";
            //echo "statut :" . $zip->status . "\n";
            $zip->close();
        }
    }

}
