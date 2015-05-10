<?php

class data_style
{
    var $id;

    var $decimal_places;
    var $min_int_digit;
    var $maps;
    var $prefix;
    var $suffix;

    function __construct($id)
    {
        $this->id = $id;
    }

    function data_style_set_prefix($prefix)
    {
        // Remove euro sign
        $prefix = str_replace(chr(0xE2) . chr(0x82) . chr(0xAC), "", $prefix);

        if ($prefix != " " && $prefix != "  " && $prefix != "  " && $prefix != "   ")
            $this->prefix = $prefix;
    }

    function data_style_set_suffix($suffix)
    {
        // Remove euro sign
        $suffix = str_replace(chr(0xE2) . chr(0x82) . chr(0xAC), "", $suffix);

        if ($suffix != " " && $suffix != "  " && $suffix != "  " && $suffix != "   ")
            $this->suffix = $suffix;
    }

    // Returns the format code of the data style
    function format_code()
    {
        $code = "";
        for ($i = 0; $i < $this->min_int_digit; $i++) {
            $code .= '0';
        }
        $is_first = true;
        for ($i = 0; $i < $this->decimal_places; $i++) {
            $code .= ($is_first) ? '.' : '';
            $code .= '0';
            $is_first = false;
        }

        return $this->prefix . $code . $this->suffix;
    }
}

class style
{
    // Nom du style
    var $name;
    // Array contenant les donn�es de style pour le texte
    var $styles;
    // Reference to an existing data-style
    var $data_style_name;
    // Style name
    var $parent_style_name;

    function style($myName)
    {
        $this->name = $myName;
        $this->styles = array();
    }

    /**
     * Merges the another style into the style. Option: overriding existing properties
     */
    function style_merge($style, $override = false)
    {

        $style_data_style_name = $style->data_style_name;

        // Merges data style name
        if ($override && $style_data_style_name != '' ||
            $this->data_style_name == ''
        ) {
            // If data style is empty OR override is on and new value
            $this->data_style_name = $style_data_style_name;
        }

        // Merges properties
        if ($override) {
            $this->styles = array_merge($this->styles, $style->styles);
        } else {
            $this->styles = array_merge($style->styles, $this->styles);
        }


    }

    /**
     * Renvoi le code CSS de l'objet style
     */
    function printStyle()
    {
        $name = $this->name;
        $styleCode = '.' . $name . "\n" . '{' . "\n";

        $prop = $this->styles;

        // Certains styles n'ont pas de propri�t�s
        if (is_array($prop)) {
            foreach ($prop as $key => $value) {
                /*
                        if($key == 'border' || $key == 'border-top' || $key == 'border-bottom' || $key == 'border-right' || $key == 'border-left') $styleCode .= '  border-style:solid;'."\n".'  border-width:thin;'."\n" ;
                */
                $styleCode .= '    ' . $key . ':' . $value . ';' . "\n";
            }

            $styleCode .= '}' . "\n";

            return $styleCode;
        } else {
            return false;
        }
    }

    /**
     * Renvoi le code CSS de l'objet style
     */
    function style_print($exclude = array())
    {
        $name = $this->name;
        $style_code = '';

        $prop = $this->styles;

        $exclude = array_flip($exclude);

        // Certains styles n'ont pas de propri�t�s
        if (is_array($prop)) {
            $css_properties = "";
            foreach ($prop as $key => $value) {
                if (!array_key_exists($key, $exclude))
                    // If the key is not excluded
                    $css_properties .= '    ' . $key . ':' . $value . ';' . "\n";
            }
            // If they are some properties, creates style code
            if ($css_properties != '')
                $style_code = '.' . $name . ' { ' . "\n" . $css_properties . ' }' . "\n";
        }

        return $style_code;
    }

    /*
     Ajoute des styles � un objet d�j� existant
    */
    function addStyles($newStyles)
    {
        $i = count($this->styles);
        if (is_array($newStyles)) {
            foreach ($newStyles as $key => $value) {
                $this->styles[$key] = $value;
            }
        }
    }

    /*
     Ajoute des styles ODS � un objet
    */
    function addOdsStyles($myOdsStyles)
    {
        $i = 0;
        foreach ($myOdsStyles as $key => $value) {
            $propName = '';
            $propValue = '';

            switch ($key) {
                case 'FO:FONT-WEIGHT':
                    $propName = 'font-weight';
                    $propValue = $value;
                    break;
                case 'FO:FONT-STYLE';
                    $propName = "font-style";
                    $propValue = $value;
                    break;
                case 'FO:BACKGROUND-COLOR';
                    $propName = "background-color";
                    $propValue = $value;
                    break;
                case 'FO:TEXT-ALIGN';
                    $propName = "text-align";
                    $propValue = $value;
                    break;
                case 'FO:BORDER-TOP';
                    $propName = "border-top";
                    $propValue = $value;
                    break;
                case 'FO:BORDER-RIGHT';
                    $propName = "border-right";
                    $propValue = $value;
                    break;
                case 'FO:BORDER-BOTTOM';
                    $propName = "border-bottom";
                    $propValue = $value;
                    break;
                case 'FO:BORDER-LEFT';
                    $propName = "border-left";
                    $propValue = $value;
                    break;
                case 'FO:BORDER';
                    $propName = "border";
                    $propValue = $value;
                    break;
                case 'FO:COLOR';
                    $propName = "color";
                    $propValue = $value;
                    break;
                case 'FO:FONT-SIZE';
                    $propName = "font-size";
                    $propValue = $value;
                    break;
                case 'STYLE:ROW-HEIGHT';
                    $propName = "height";
                    $propValue = $value;
                    break;
                case 'STYLE:COLUMN-WIDTH';
                    $propName = "width";
                    $propValue = $value;
                    break;
                case "STYLE:FONT-NAME";
                    $propName = "font-family";
                    $propValue = $value;
                    if ($value == 'Arial1') $propValue = "Arial";
                    if ($value == 'Arial2') $propValue = "Arial";
                    if ($value == 'Arial3') $propValue = "Arial";
                    break;
                case "STYLE:TEXT-UNDERLINE-STYLE":
                    if ($value != 'none') {
                        $propName = "text-decoration";
                        $propValue = "underline";
                    }
                    break;
            }
            if ($propName != '') $cssStyles[$propName] = $propValue;
            $i++;
        }
        if (isset($cssStyles)) $this->addStyles($cssStyles);
    }
}
