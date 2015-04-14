<?php

/**
 * @file
 * Provides the Appizy functions to handle Spreadsheet files
 */

/**
 * Converts XLSX spreadsheet into ODS
 * Uses CloudConvert API
 */
function appizy_ss_convert($file_name, $file_ext, $save_dir) {

  require_once ('CloudConvert.class.php');

  // insert your API key here
  $apikey= "oUrlr4Pomv-WkXkgdgkvqLlbEVoe1miY7pYwu2gH0nBGyeaAwA70CNdQpBWkA1ColZtQ08ArH26bUFLJd_yIWQ";
  $apikey= "vs6kC7I_xA-ufneYKA2L17VS7V5N2nieK0VUxBnKUxAvbVa89epGuHrTEZMloZZiCIf5Zg3XIqB-UB6mKq7hTA";

  $process = CloudConvert::createProcess($file_ext, "ods", $apikey);

  $file_dir = $save_dir.'/'.$file_name.'.ods';
  
  // set some options here...
  // $process -> setOption("email", "1");

  $process-> upload($save_dir.'/'.$file_name . '.' . $file_ext, "ods" );

  if ($process-> waitForConversion()) {
    $process -> download($file_dir);
    //$workbook = $workbook_dir."/output.ods";

  }
  return $file_dir;
}

/**
 * Deflates a spreadsheet file
 */
function appizy_ss_extract($workbook, $extract_dir) {

  // Deflate package
  $zip = new ZipArchive;
  $zip->open($workbook);
  $zip->extractTo($extract_dir);
  $zip->close();

  $xml_path[] = $extract_dir."/styles.xml";
  $xml_path[] = $extract_dir."/content.xml";


  return $xml_path;

}

