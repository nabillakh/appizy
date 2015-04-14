(function() {
  
  /**
   * Extends jQuery
   */
  $.fn.exists = function (){
		return this.length !== 0;
	};
  
  RANGE = function(){
    if (arguments.length == 1) {
      cell_ref = 's'+arguments[0][0]+'r'+arguments[0][1]+'c'+arguments[0][2];

      var item = $('[name='+cell_ref+']');

      if (item.exists()) {

          values =  get_input(item.val(), item.data('type'));
      } else {
          values = null;
      }
    } else if (arguments.length == 2) {
      head = arguments[0] ;
      tail = arguments[1] ;
      var values = [];
      for (var col = 0; col <= (tail[1]-head[1]); col++) {
        for (var row = 0; row <= tail[2]-head[2]; row++) {
          cell_ref = 's'+head[0]+'r'+(head[1]+col)+'c'+(head[2]+row);

          var item = $('[name='+cell_ref+']');

          if (item.exists()) {

            values.push(get_input(item.val(), item.data('type')));

          } else {

            values.push(null);

          }
        }
      }
    }
    return values;
  };

  get_input = function(value, type){

    if (typeof type === "undefined") type = "string";
    
    if (type == "boolean") {
      value = (value == "true");
    } else if (value.length === 0) {
      value = "";
    } else if (type == "string") {
      
      if (!isNaN(value) && isFinite(value)) {
        value = parseFloat(value);
      } else {

      }

    } else {
      old_format = value;
      value = numeral().unformat(value);
    }
    return value;
  };
  
  /**
   * Set the value of an output according to it's type and format
   */
  setOutput = function(output_name, value, type) {
  
    // Set default type if necessary
    if (typeof type === "undefined") {
      type = (typeof value === "undefined") ? "string" : typeof value ;
    }
    
    var element = $('[name='+output_name+']');
    var formats = $(element).data('format');

    element.data('type', type);
    
    // Format allowed for number, float and percentage
    if ((type == "number" || type == "float" || type == "percentage") && (typeof formats != "undefined")) {
      
       var formats_array = formats.toString().split(";",3);
       var nb_format = formats_array.length;   
       if(value==0 && nb_format==3){ myformat = formats_array[1]; } else if(value<0) { myformat = formats_array[0]; } else { myformat = formats_array[nb_format-1] ;}
   
       element.val(numeral(value).format(myformat));
 
    } else {
      element.val(value);
    }
  };

}).call(this);