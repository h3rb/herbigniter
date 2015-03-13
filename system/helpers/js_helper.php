<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright		Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/HI_user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Persistent Hashing Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Javascript
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/js_helper.html
 */

// ------------------------------------------------------------------------


if(!function_exists('js'))
{   
    function js( $code ) {
      return '<script type="text/javascript"> ' . $code . " </script>";
    }
}

if(!function_exists('add_event'))
{   
// Append an event to an existing Javascript event handler.
// o=javascript object
// e=object event
// f=new function
// * - see variation, add_event_div
   if ( !isset($add_event_counter) )      $add_event_counter=1;
   function add_event( $o, $e, $f ) {  global $add_event_counter;
       $add_event_counter++;
       return '<script language="javascript" type="text/javascript"> '
            . 'var oldE' . $add_event_counter . ' = ' . $o . '.' . $e . '; '
	    . $o . '.' . $e . ' = function() { oldE' . $add_event_counter . '(); ' . $f
	                                .  ' }; </script> ';
   }
}

if(!function_exists('add_event_id'))
{   
// Append an event to an existing Javascript event handler on given object (usually a div with an id).
// o=javascript object
// e=object event
// f=new function
// * - see variation, add_event_div   
   if ( !isset($add_event_id_counter) )    $add_event_id_counter=1;
   function add_event_id( $id, $e, $f ) { global $add_event_id_counter;
       $add_event_id_counter++;
       return '<script language="javascript" type="text/javascript"> '
            . 'var oldEid' . $add_event_id_counter . ' = getElementbyId(\'' . $id . '\').' . $e . '; '
	    . 'getElementbyId(\'' . $id . '\').' . $e . ' = function() { oldEid' . $add_event_counter . '(); ' . $f
	                                .  ' }; </script> ';
   }
}



if(!function_exists('alert'))
{      
   function alert( $message ) {
	     return '<script language="javascript" type="text/javascript"> alert( "' . $message . '" ); </script>';
   }
}

if(!function_exists('jsredirect'))
{
function jsredirect( $url, $delay = 0 ) {
	if ( $delay == 0 ) 
	   	 return '<script type="text/javascript"> window.location = "' . $url . '"; </script>';
	else return '<script type="text/javascript"> function delayer(){ window.location = ' . "'"
	        . $url . "'; } setTimeout('delayer()', '" . $delay . "'" . '); </script>';
}
}

if(!function_exists('setup_tinymce'))
{
   if ( !isset($add_onload_counter) ) $add_onload_counter=1;
   function add_onload( $t ) { global $add_onload_counter;
	return '<script language="javascript" type="text/javascript"> 	var oldOnload' . $add_onload_counter
	   . ' = window.onload; window.onload = function() { oldOnload' . $add_onload_counter . '(); '
	   . $t
	   .  ' }; </script> ';
   }
}

   /*
    * Establish a text area script with a character count box,
    * use js char_counter() in the onkeyup
    */
if(!function_exists('js_character_counter'))
{
   function js_character_counter( $size="255", $dom_id="text", $div_id="counter" ) {
      return '
<script language=javascript>
 var count = "' . $size . '";
 function ' . $div_id . '_jscounter(){ var tex = document.getElementById(\'' . $dom_id . '\').value; var len = tex.length;
  if(len > count) {  tex = tex.substring(0,count);  document.myform.comment.value =tex; return false; }
  document.getElementById(\'' . $div_id . '\').innerHTML=""+tex.length;
  document.myform.limit.value = count-len;
 } </script>
';
   }
}

if(!function_exists('setup_tinymce'))
{
    // use tinymce
    function setup_tinymce( $path=NULL ) { $Herb =& get_instance();
	if ( is_null($path) ) $path = here().'/js';
	return '<script type="text/javascript" src="' . $path . '/tiny_mce.js"></script>' . 
               '<script type="text/javascript">' .
               'tinyMCE.init({mode : "textareas", theme : "simple" });' .
               '</script>';
    }
}

if(!function_exists('tinymce'))
{
    function tinymce( $name = "elm1", $content="" ) {
             return '<textarea id="' . $name . '" name="' . $name . '" rows="15" cols="80" style="width: 80%">' 
                    . $content . '</textarea>';
    }
}

   


// WZJSG Bresenham function mappings
// WZ Javascript Vector Graphics Library
// Source: http://www.walterzorn.com/index.htm

  // called in <HEAD>
if(!function_exists('use_zorng'))
{	
  function use_zorng( $path=NULL ) { $Herb =& get_instance();
	if ( is_null($path) ) $path = here().'/js';
       return '<SCRIPT src="' . $path . '/wz_jsgraphics.js" type=text/javascript></SCRIPT>
';	  
  }
}

if(!function_exists('wz_start'))
{	
       function wz_start( ) { return "<SCRIPT type=text/javascript>var jg = new jsGraphics();
"; }
}

if(!function_exists('wz_draw'))
{	
       function wz_draw( $jsid, $code ) {
	return "<SCRIPT type=text/javascript>" .
	"function jsid() { var jg = new jsGraphics() " .
	$code . "}";
       }
}


if(!function_exists('fillRect'))
{
    function fillRect( $left, $top, $width, $height ) {
	    return '  jg.fillRect( ' . $left . ', ' . $top . ', ' . $width . ', ' . $height . ');
';
    }
}

if(!function_exists('drawRect'))
{
    function drawRect( $left, $top, $width, $height ) {
	    return '  jg.fillRect( ' . $left . ', ' . $top . ', ' . $width . ', ' . $height . ');
';
    }    
}

if(!function_exists('setColor'))
{
    function setColor ( $color ) { 
	    return '  jg.setColor("' . $color . '");
';
    }
}

if(!function_exists('drawText'))
{    
    function drawText( $content, $x, $y ) {
        return '  jg.drawString("' . $content . '", ' . $x . ', ' . $y . ');
';
    }
}

if(!function_exists('textRect'))
{
    function textRect( $content, $x, $y, $w, $alignment="left" /* or "right" */ ) {	
	    return '  jg.drawStringRect( ' . $content . ', ' . $x . ', ' . $y . ', ' . $w . ', "' . $alignment . '");
';
    }    
}

if(!function_exists('stroke'))
{   
//    jg.setStroke(Stroke.DOTTED);
    function stroke( $thickness ) {
	    return '  jg.setStroke(' . $thickness . ');
';
    }
}

if(!function_exists('line'))
{    
    function line( $x, $y, $xx, $yy ) {
	    return '  jg.drawLine(' . $x . ', ' . $y . ', ' . $xx . ', ' . $yy . ');
';
    }
}

if(!function_exists('jghtm'))
{   
//jg.htm += '<div style="position:relative;width:50px;height:13px;"><table cellpadding="0" cellspacing="0"><tr><td>';
//jg.htm += '<\/td><\/tr><\/table><\/div>';
	function jghtm( $html_insert ) {
		return '  jg.htm += ' . "'" . $html_insert . "'" . ';
';
	}
}

if(!function_exists('polyline'))
{	
	// arrays entered as values delimited with ,s -> must be equal length
	function polyline( $array1, $array2 ) {
		return '  jg.drawPolyLine(new Array( ' . $array1 . ', ' . $array2 . ');
';
	}
}

if(!function_exists('polygon'))
{	
	function polygon( $array1, $array2 ) {
		return '  jg.drawPolygon(new Array(' . $array1 . '), new Array(' . $array2 . ');
';
	}
}

if(!function_exists('fillPolygon'))
{	
	function fillPolygon( $array1, $array2 ) {
		return '  jg.fillPolygon(new Array(' . $array1 . '), new Array(' . $array2 . ');
';
	}
}

if(!function_exists('oval'))
{		
	function oval( $x, $y, $xx, $yy ) {
	    return '  jg.drawOval(' . $x . ', ' . $y . ', ' . $xx . ', ' . $yy . ');
';
	}
}

if(!function_exists('fillOval'))
{		
	function fillOval( $x, $y, $xx, $yy ) {
	    return '  jg.fillOval(' . $x . ', ' . $y . ', ' . $xx . ', ' . $yy . ');
';
	}
}

if(!function_exists('ellipse'))
{		
	function ellipse( $x, $y, $xx, $yy ) {
	    return '  jg.drawEllipse(' . $x . ', ' . $y . ', ' . $xx . ', ' . $yy . ');
';
	}
}

if(!function_exists('fillEllipse'))
{		
	function fillEllipse( $x, $y, $xx, $yy ) {
	    return '  jg.fillEllipse(' . $x . ', ' . $y . ', ' . $xx . ', ' . $yy . ');
';
	}
	
}

if(!function_exists('arc'))
{		
	function arc( $x, $y, $xx, $yy, $sa, $ea ) {
	    return '  jg.drawArc(' . $x . ', ' . $y . ', ' . $xx . ', ' . $yy . ', ' . $sa . ', ' . $ea .');
';
	}
}

if(!function_exists('fillArc'))
{	
	function fillArc( $x, $y, $xx, $yy, $sa, $ea ) {
	    return '  jg.fillArc(' . $x . ', ' . $y . ', ' . $xx . ', ' . $yy . ', ' . $sa . ', ' . $ea .');
';
	}		
}

if(!function_exists('jgimage'))
{		
	function jgimage( $image_url, $x, $y, $xx, $yy ) {
        return '  jg.drawImage( ' . $image_url . ', ' . $x . ' ,' . $y . ', ' . $xx . ', '. $yy . ' );
';
	}
}

if(!function_exists('clear'))
{		
	function clear( ) { 
		return '  jg.clear();
';
	}
}

if(!function_exists('setFont'))
{
	  /*
Font.PLAIN for normal style (not bold, not italic)
Font.BOLD for bold fonts
Font.ITALIC for italics
Font.ITALIC_BOLD or Font.BOLD_ITALIC to combine the latters.
	  */
  function setFont( $name = "verdana,geneva,sans-serif", $size = "10px", $drawstyle = "PLAIN" ) {
	  return '  jg.setFont("' . $name . '", "' . $size . '", Font.' . $drawstyle . ');
';
  }
}

if(!function_exists('newCanvas'))
{	
  if ( !isset($canvas) ) $canvas = "myCanvas";
  function newCanvas( $name = "myCanvas",  $zg_height = "100%",  $zg_width  = "100%"  )   {	global $canvas;
	  $canvas=$name;
	  return '<div id="' . $canvas . '" style="position:relative;height:' . $zg_height . ';width:' . $zg_width . ';"></div>';
  }
}

if(!function_exists('wz_end'))
{	
       function wz_end( ) { return " jg.paint(); </SCRIPT>
"; }
}



/* End of file js_helper.php */
/* Location: ./system/helpers/js_helper.php */