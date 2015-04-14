<?php

/**
 * @file
 * The theme system, which controls the output of Appizy.
 */


/**
 * Renders a tool in standart layout
 */
function appizy_render($template, $variables = array(), $preview = FALSE) {
  // Variables default values
  $alerts = FALSE;
  $libraries = FALSE;
  $style = FALSE; $content = FALSE; $script = FALSE;
  $title = "Appizy";

  //$mess_success = FALSE; $mess_warn = FALSE; $mess_error = FALSE;

  // Import variables in local
  extract($variables, EXTR_OVERWRITE);

  // Start output buffering
  ob_start();

  // Include the template file
  include('template/'.$template);

  // End buffering and return its contents
  return ob_get_clean();
}

function appizy_render_template($header, $content, $footer) {

  ob_start();

  print(file_get_contents('template/'.$header, FILE_USE_INCLUDE_PATH));
  print $content;
  print(file_get_contents('template/'.$footer, FILE_USE_INCLUDE_PATH));

  // End buffering and return its contents
  return ob_get_clean();
  
  
}
function appizy_render_preview($template, $variables = array()) {
  $app_code = appizy_render($template, $variables);
  $app_code = _render_oneline($app_code);

  return $app_code;
}

function bs_grid($sheet, $jumbotron = FALSE) {

  $id = $sheet->get_id();

  $div_class = "appizy-section appizy-section-$id";
  if( $jumbotron)
    $div_class = 'jumbotron appizy-section-first';

  $html = '';
  $nl = "\n";

  $html .= '<div id="'.$id.'" class="'.$div_class.'">';
  $html .= '<div class="container">';
  $html .= '<h1>'.$sheet->get_sheet_name().'</h1>';

  $max_length = 0;

  foreach($sheet->row as $row) {
    // First loop, check the longest row
    $row_length =  $row->row_length();
    $max_length = $row_length > $max_length ? $row_length : $max_length;
  }

  // Calculates the size of column in the Bootstrap grid
  $col_size = floor(12/$max_length);

  foreach($sheet->row as $name => $row) {
    $row_class = '';
    $row_style = $row->get_styles_name();
    // if($row_style != '') $row_class .= " ".$row_style;


    $html .= '<div class="row">'.$nl;

    // Second loop, construct HTML table
    $cells = $row->row_get_cells();
    foreach($cells as $cell) {
      $cell_size = $cell->cell_get_colspan();
      $cell_value = $cell->cell_get_value();

      // Ajout du style de la cellule
      $cell_class = '';
      $cell_style =  $cell->get_styles_name();
      if($cell_style != '') $cell_class .= " ".$cell_style;

      $html .= '<div class="col-md-'.($cell_size*$col_size).$cell_class.$row_class.'"><p>'.$cell_value.'</p></div>'.$nl;
    }
    $html .= '</div>';

  }
  $html .= '</div>'.$nl.'</div>';
  
  return $html;
}

function bs_navigation($sections, $navid) {
  
  $template = "bs-navigation.tpl.php";

  ob_start();

  // Include the template file
  include('template/'.$template);

  // End buffering and return its contents
  return ob_get_clean();

}
/**
 * Transform indented HTML code to online.
 */
function _render_oneline($code) {
  $oneline = $code;
  // Remove comments
  $oneline = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $oneline);
  // Remove tabs, spaces, newlines, etc.
  $oneline = str_replace(array("\r\n", "\r", "\n"), '', $oneline);
  
  $comment = '<!-- To get clean working source, please download the file using "Download" button. -->';

  $oneline = $comment."\n".$oneline;

  return $oneline;
}
