<?php
class TableElement {
  // Index of the TableElement
  var $eid;
  // Array containing styles name of the TableElement
  var $styles_name = array();

  function __construct($element_id){
    $this->set_id($element_id);
    $this->styles_name = array();
  }
  function tabelmt_error($message) {
    trigger_error( __CLASS__.': '.$message, E_USER_WARNING );
    //$this->error = $message;
  }
  function tabelmt_debug($message) {
    trigger_error( __CLASS__.': '.$message );
    //$this->error = $message;
  }

  function set_id($element_id) {
    $this->eid = (int) $element_id;
  }
  function get_id() {
    return $this->eid;
  }
  function add_style_name($new_style_name) {
    $this->styles_name[] = $new_style_name;
  }
  /**
   * Returns styles name concatained with a $separator
   */
  function get_styles_name($separator = " ") {
    $styles_name = "";
    $is_first = TRUE;
    foreach($this->styles_name as $name) {
        $styles_name .= ($is_first) ? $name : " ".$name ;
        $is_first = FALSE;
    }
    return $styles_name ;
  }
  function get_styles() {
    return $this->styles_name;
  }
}
class sheet extends TableElement {
  var $name;
  var $style;
  var $col;
  var $row;

  function sheet($sheet_id, $sheet_name) {
    $this->set_id($sheet_id);
    //
    $this->name = $sheet_name;
    $this->style = "";
    $this->col = array();
    $this->row = array();
  }
  function addCol(column $newCol) {
    $col_ind = $newCol->get_colid();
    $this->col[$col_ind] = $newCol;
  }
  function addRow(row $newRow) {
      $row_ind = $newRow->get_rowind();
      $this->row[$row_ind] = $newRow;
  }
  function getCol($col_key) {
      $column  = FALSE;
      if (array_key_exists($col_key,$this->col)) {
          $column = $this->col[$col_key];
      }
      return $column;
  }
  /**
   * Returns sheet cols
   */
  function sheet_get_cols() {
    return $this->col;
  }
  function getRows() {
      return $this->row ;
  }
  function getRow($key_row) {
      return $this->row[$key_row];
  }
  function sheet_get_row($row_ind) {
		$rows = $this->row;
		$row = FALSE;
		if (array_key_exists($row_ind, $rows)) {
			$row = $rows[$row_ind];
		} else {
			$this->tabelmt_debug("Unexistent row: s".$this->get_id()."r$row_ind");
		}
		return $row;
	}
  function sheet_get_cell($row_ind,$col_ind) {
    $cell = FALSE;

		$row = $this->sheet_get_row($row_ind);
    
    if ($row) {

      $cell = $row->row_get_cell($col_ind);
      if(!$cell) {
        $this->tabelmt_debug("Unexistent cell: s".$this->get_id()."r$row_ind"."c$col_ind");
      }
		}
		return $cell;
	}
  function setRows($new_rows)
  {
      $this->row = $new_rows ;
  }
  function get_sheet_name() {
    return $this->name;
  }
  function getName()
  {
      return $this->name;
  }
  function sheet_clean() {
    
    $isFirstFilled = FALSE ;
    $offset = 0 ;
    // On inverse les rows
    $rows = $this->getRows();
    $row_nb = count($rows);
    
    $rows_reverse = array_reverse($rows,TRUE);

    // On nettoie ensuite chaque row
    foreach($rows_reverse as $temprow) {

      $temprow->cleanRow();

      if (!$isFirstFilled) {
        if ($temprow->isEmptyRow()) {
          $offset++ ;
        } else {
          $isFirstFilled = TRUE ;
        }
      }

    }
    // $this->tabelmt_debug("Clean sheet s".$this->get_id()."offset:$offset");

    // On supprime les $offset premires $sheet vides
    if ($offset>0) $rows = array_slice($rows, 0, $row_nb - $offset);

    $this->setRows($rows);
  }
  function isEmptySheet()
  {
      $rows = $this->getRows();
      return empty($rows);
  }
  function cellExistsInSheet($coord = array())
  {
      $key_row = $coor['row'] ;
      if(array_key_exists($key_row,$this->getRows()))
      {
          $row = $this->getRows($key_sheet);
          return $row->cellExistInRow(array_slice($coord,1));
      } else
      {
          return FALSE ;
      }
  }
}
class column extends TableElement{
  var $colid;
  var $collapse;
  var $default_cell_style;

  function column($colid) {
    $this->colid = $colid;
    $this->default_cell_style = "";
    $this->collapse = FALSE;
  }
  function get_colid() {
    return $this->colid;
  }
  function col_set_default_cell_style($newStyle){
    $this->default_cell_style = $newStyle;
  }
  function col_collapse() {
      $this->collapse = TRUE;
  }
  function get_collapsed() {
      return $this->collapse == TRUE;
  }
  function col_get_default_cell_style() {
    return $this->default_cell_style;
  }
}
class row extends TableElement{
  var $name;
  var $sheet_ind;
  var $row_ind;
  var $cells;
  var $collapse;

  function row($sheet_ind, $row_ind, $options) {
    $this->set_id($row_ind);

    $this->sheet_ind = $sheet_ind;
    $this->row_ind = $row_ind;
    $this->name = 's'.$sheet_ind.'r'.$row_ind;


    $this->cell = array();

		if(isset($options['collapse'])) $this->collapse = $options['collapse'];
    if(isset($options['style'])) $this->add_style_name($options['style']);
  }
  function addCell(cell $newCell) {
    $cell_id = $newCell->get_id();
    $this->cell[$cell_id] = $newCell;
  }
  function getCells(){ return $this->cell;  }

  function row_get_cells(){ return $this->cell;  }

  function row_get_cell($cell_ind) {
    if (array_key_exists($cell_ind, $this->cell)) {
      return $this->cell[$cell_ind];
    } else {
      $this->tabelmt_debug("Unexistent cell r".$this->get_id()."c$cell_ind");
      return FALSE;
    }
  }
  /**
   * Returns the number of cells in a row
   */
  function row_nbcell() {
    return count($this->row_get_cells());
  }
  /**
   * Returns the size of a row = nb of cells + colspan
   */
  function row_length() {
    $length = 0;
    $cells = $this->row_get_cells();

    foreach($cells as $cell) {
      $rowspan = $cell->cell_get_colspan();
      $length += $rowspan;
    }

    return $length;
  }

  function get_rowind() { return $this->row_ind ; }

  function get_cell($cell_ind) {
      return $this->cell[$cell_ind];
  }
  function getName(){  return $this->name;  }
  function getStyle(){  return $this->style; }

  function row_set_cell($new_cells) {
    $this->cell = $new_cells ;
  }
  
  function cleanRow() {

    $isFirstFilled = FALSE ;
    $offset = 0 ;
    // On inverse les cells
    $cells_reverse = array_reverse($this->getCells(), TRUE);

    // On nettoie ensuite chaque row
    foreach($cells_reverse as $tempcell) {
      if(!$isFirstFilled) :
        if ($tempcell->cell_isempty()) {
          $offset++ ;
        } else {
          $isFirstFilled = TRUE ;
        }
      endif;
    }
    // On supprime les $offset premires $sheet vides
    if ($offset > 0) $cells_reverse = array_slice($cells_reverse,$offset);
    // On inverse a nouveau et on affecte les sheets du tableau
    $cells = array_reverse($cells_reverse, TRUE);
    $this->row_set_cell($cells);
    
  }
  
  function isEmptyRow() {
    $cells = $this->getCells();
    return empty($cells);
  }

  function cellExistInRows($coord = array()) {
      return array_key_exists($coord['cell'],$this->getCells());
  }

  function collapseRow() {
      $this->collapse=TRUE;
  }
}
class cell extends TableElement {
  var $coord; // CoordonnŽes de la cellule sheet,row,col - identifie de faon unique la cellule
  var $type; // Type de cellule pour Appizy : text, in, out
  var $value_type; // Type de valeur de la cellule : string, float, boolean
  var $value_attr; // Valeur de la cellule
  var $value_disp; // Valeur vue (il peut y avoir une diffŽrence de mise en forme avec $value_attr)
  var $value_inlist; // Liste de l'ensemble des valeurs de la cellule, si vide valeur libre

  var $validation;
  
  var $formula; // Formule associŽe ˆ la cellule. La formule est stockŽe en langage javascript
  // Colonnes et lignes fusionnŽes
  var $colspan;
  var $rowspan;
  // Nom du style (CSS) associŽ ˆ la cellule
  var $styles = array() ;
  // Comment on the cell
  var $annotation;

  function cell($sheet, $row, $col, $options = array() ) {
    $this->set_id($col);
    //
    $this->coord = array('sheet'=>$sheet,
                          'row'=>$row,
                          'col'=>$col
                          );

    if(isset($options['style'])) $this->add_style_name($options['style']);

    $this->value_disp = isset($options['value_disp']) ?
      $options['value_disp'] : "";
    $this->value_attr = isset($options['value_attr']) ?
      $options['value_attr'] : "";
    $this->type = isset($options['type']) ?
      $options['type'] :"text";
    $this->value_type = isset($options['value_type']) ?
      $options['value_type'] : "string";
    $this->rowspan = isset($options['rowspan']) ?
      $options['rowspan'] : 1;
    $this->colspan = isset($options['colspan']) ?
      $options['colspan'] : 1;
    $this->validation = isset($options['validation']) ?
      $options['validation'] : NULL;
    $this->collapse=FALSE;
    $this->value_inlist = array();
    $this->annotation = isset($options['annotation']) ?
      $options['annotation'] : '';

    if (isset($options['formula'])) {
      $this->formula = $options['formula'];
      $this->type = "out";
    }
  }

  /*
   Editeurs
  */
  function setValueType($myValueType)
  {
      $this->valueType=$myValueType;
  }
  function setValue($myValue)
  {
      $this->value_disp=$myValue;
  }
  function setValueAttr($myValueAttr) {
      $this->value_attr = $myValueAttr;
  }

  function setFormula($myFormula)
  {
      $this->formula=$myFormula;
  }

  function cell_set_type($myType) {
    $this->type = $myType;
  }
  function addStyle($style_name = NULL)
  {
      $this->styles[] = $style_name;
  }
  function setValueInList($myList) {
      $this->value_inlist = $myList;
  }
  /**
   * Accesseurs
   */
  function cell_get_annotation(){
		$annotation = $this->annotation;

		// Just gets the content inside p tags.
		if($annotation) {
			preg_match('/\>(.*)<\/p>/', $annotation, $matches);
			$annotation = strip_tags($matches[1]);
		}
		
		return $annotation;
	}

  function cell_get_validation(){ return $this->validation; }
  
  function getName()
  {
      $name = 's'.$this->coord['sheet'].'r'.$this->coord['row'].'c'.$this->coord['col'];
      return $name;
  }
  function cell_value_type() {
      return $this->value_type;
  }
  /**
   * Return cell attribute value first or displayed value if not existent
   */
  function cell_get_value() {

		$cell_value = ($value_attr = $this->value_attr) ?
			$value_attr : $this->value_disp;

		if ($this->type == "in")
			$cell_value = strip_tags($cell_value);

		return $cell_value;
  }
  /**
   * Return cell displayed value
   */
  function cell_get_value_disp() {
    return $this->value_disp ;
  }
  /**
   * Return cell attributed value
   */
  function cell_get_value_attr() {
    return $this->value_attr ;
  }
  function getValueList() {
    return $this->value_inlist;
  }
  function getFormula()
  {
      return $this->formula ;
  }
  function getType()
  {
      return $this->type;
  }
  function cell_get_colspan() {
    return $this->colspan;
  }
  function cell_get_rowspan() {
    return $this->rowspan;
  }
  function getStyle() {
      $style = "";
      $is_first = TRUE;
      foreach($this->styles as $style_name) {
          $style .= ($is_first) ? $style_name : " ".$style_name ;
          $is_first = FALSE;
      }
      return $style ;
  }
  function collapseCell() {
      $this->collapse = TRUE;
  }

  function guessType() {
      if($this->getFormula()!=NULL) {
          $this->type="out";
          // Si formule dans la cellule, lexage de la formule pour deviner les interdŽpendants

          // On rŽcupre les cellules dŽpendantes

          // setType des dŽpendants en input sauf si il y a dŽjˆ une formule !
      }
  }
  
  function isFormula() {
      return ($this->getType()=="out");
  }
  
  function cell_isempty() {
    $empty = (
      $this->get_styles_name() == '' &&
      $this->cell_get_value() == '' &&
      $this->cell_get_validation() == '' &&
      $this->getType() != 'out'
    );

    return $empty ;
  }
}
?>