<?php

// Dependancies


// Childs parser
include('Odoc_wbparser.class.php');
//include('oxml_wbparser.class.php');

class Wbparser {

  var $wb_sheets = array();
  var $wb_formulas = array();
  var $styles = array();

  var $current_sheet;
  var $current_row;

  var $debug;
  
  function __construct($debug = TRUE) {
    $this->debug = $debug ;
    $this->parser_debug("New WBparser");
  }

  /**
   * Adds a new sheet to the Parser
   */
  function add_sheet($sheet_id, $sheet_name) {
    $new_sheet = new sheet($sheet_id, $sheet_name);
    $this->wb_sheets[$sheet_id] = $new_sheet;
  }
  /**
   * Adds a new row to the selected wb_sheet
   */
  function add_row($sheet_ind, $row_ind, $options) {
    // Create a new row
    $new_row = new row($sheet_ind, $row_ind, $options);
    // Get selected sheet
    $sheet = $this->wb_sheets[$sheet_ind];
    $sheet->addRow($new_row);
  }
  function add_cell($sheet_ind, $row_ind, $col_ind, $options) {
    // Create a new cell
    $new_cell = new cell($sheet_ind, $row_ind, $col_ind, $options);
    // Get selected row in the selected sheet
    $sheet = $this->wb_sheets[$sheet_ind];
    $row = $sheet->getRow($row_ind);
    $row->addCell($new_cell);
  }
  function add_formula($new_formula) {
    $this->wb_formulas[] = $new_formula;
  }
  function add_col($sheet_ind, column $new_col) {
    $sheet = $this->wb_sheets[$sheet_ind];
    $sheet->addCol($new_col);
  }
  /**
   * Trigger an error message in host log
   */
  function parser_error($message) {
    trigger_error( __CLASS__.': '.$message, E_USER_WARNING);
  }
  function parser_debug($message) {
    if ($this->debug) trigger_error( __CLASS__.': '.$message);
  }
}