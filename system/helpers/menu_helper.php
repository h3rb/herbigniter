<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright           Copyright (c) 2009 Gudagi
 * @license		http://gudagi.net/herbigniter/HI_user_guide/license.html
 * @link		http://gudagi.net/herbigniter/
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------
// The Macromedia Rollover Buttons Script, Automated
// Copyleft 2009 H. Elwood Gilliland
// Some rights reserved by Adobe, but we're not sure which
// Anyway, I own a license.
//
// Notes on the use of this with HerbIgniter.
//
// This helper requires you to use output buffering.  In the site template,
// found in /system/application/views/template.php, you will notice infrastructure
// for deploying a "top menu"; basically, preloading of images implemented in this
// method requires code to be inserted both into the head, and the body's onload tag,
// which point to invoked rollover source images.  Then, in the body itself, the
// code with links for the rollover animations is deployed.
//
// Using this helper is recommended when CSS fails you.  Since this is a very 
// widely adopted standard, use of rollover buttons functions on a wider range
// of browsers than just using CSS "hover".  Also, some find it easier to
// deploy menus this way, so, here it is.
//
// init_stylish_buttons()
// ; the head code that makes the rollover animations work
//
// onload_preload_images( array() )
// ; a list of image file locations for preloading, returns onload for body tag
//
// make_menu_horiz( $name, $path, $items, $filler_image, $trailing_w )
// ; generates the body code, returned, and updates the global menus variable
//   which contains the head/onload code
//
// make_menu_vert( $name, $path, $items )
// ; generates the body code, returned, and updates the global menus variable
//   which manages the preloading (head/onload code)
//
//
// ------------------------------------------------------------------------

/**
 * HerbIgniter Chart Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Helpers
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/file_helpers.html
 */

// ------------------------------------------------------------------------

/**
 * Initialize Javascript for this Helper
 *
 * Returns the required head code for this feature.
 *
 * @access	public
 * @param	none
 * @return	string
 */	
if ( !isset($menus) ) $menus=array();

//
// Called in the head of a page.
//
if ( ! function_exists('init_stylish_buttons'))
{
function init_stylish_buttons() {
  return '
<script type="text/javascript">
<!--
//v3.0
function MM_swapImgRestore() {  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc; }
function MM_preloadImages() {   var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array(); var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++) if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}} }
//v4.01
function MM_findObj(n, d) {  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {   d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);  if(!x && d.getElementById) x=d.getElementById(n); return x;}
//v3.0
function MM_swapImage() {   var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}}
//-->
</script> ';
}
}

// ------------------------------------------------------------------------
/**
 * Onload Preloader Accumulator
 *
 * Generates onload code and manages preloaded images for this feature.
 *
 * @access	public
 * @param	name	path to file
 * @param	path	path to images (no t)
 * @param	items	array[0..4] defining 'url','title','imageprefix',selected
 * @param	filler_image	path to file
 * @param	trailing_w      x-scale of filler image (pixels)
 * @return	string
 */	
//
// An accumulator function which prepares the global $menus array;
// this is basically a stack of all images to be preloaded, which
// has full paths to all image files to be pre-loaded.
if ( ! function_exists('onload_preload_images'))
{
function onload_preload_images( $images ) {
    $number = count($images);
    if ( $number == 0 ) return "";
    if ( !isset($menus['existing']) ) $menus['existing'] = $images;
    else
    foreach ( $images as $image ) array_push($menus['existing'],$image);

    $n=0;
    $output = "MM_preloadImages(";
    foreach ( $menus['existing'] as $image ) {
      $n++;
      $output .= "'".$image."'".($n<$number?',':'');
    }
    $output .= ");";
    return $output;
}
}

// ------------------------------------------------------------------------

/**
 * HerbIgniter File Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Helpers
 * @author		Herb
 * @link		http://gudagi.net/herbigniter/HI_user_guide/helpers/file_helpers.html
 */

// ------------------------------------------------------------------------

/**
 * Makes a Horizontal Menu
 *
 * Generates and manages preloaded images for a horizontal menu.
 *
 * @access	public
 * @param	string	path to file
 * @param	string	path to images (no t)
 * @param	array	array[0..4] defining 'url','title','imageprefix',selected
 * @param	string	path to file
 * @param	string  x-scale of filler image (pixels)
 * @return	string
 */	
// Implements Macromedia-style Rollover Horizontal Menu
//
// name: the prefix for the images where <name>_<imagename>_<link|hover>.png
// path: path to the image files (no trailing /)
// items: an array of arrays, where the interior array contains four items,
// 0..4 where 0=targeturl, 1=title_text, 2=imagename, 3="selected" (true/false)
// filler_image: path to the image used as filler
// trailing_w: width of the trailing image
//
// returns: menu array with three elements: menu['onload'], menu['body'] and menu['head']

if ( ! function_exists('make_menu_horiz'))
{
function make_menu_horiz( $name, $path, $items, $filler_image, $trailing_w ) { global $menus;
    if ( count($items) == 0 || strlen($name) == 0 ) return NULL;
    $preload=array();
    $n=0; $output="";
    foreach( $items as $item ) {
        $n++;
//   <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image1','','file:///',1)"><img src="file:///" alt="" name="Image1" width="x" height="y" border="0" id="Image1" /></a>
        $output .= '<a href="' . $item[0] . '" title="' . $item[1] . '" alt="' . $item[1]
                . '" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\''
                . $name . $n . '\', \'\', \'' . $path . '/' . $name . '_' . $item[2]
                . '_hover.png\',1)"><img src="' . $path . '/' . $name . '_' . $item[2]
                . (isset($item[3])&&$item[3]===true ?'_link.png' : '_hover.png')
                . '" name="' . $name . $n . '" border="0" id="' . $name . $n . '"></a>';
        $preload[] = $path . '/' . $name . '_' . $item[2] . '_hover.png';
    }
    $size= getimagesize($filler_image);
    $h = $size[1];
    $menu  = $output .     ( $trailing_w > 0 ? '<img src="' . $filler_image . '" height="' . $h . '" width="' . $trailing_w . '">' : '' );
    $menus[$name]    = $menu;
    $menus['head']   = init_stylish_buttons();
    $menus['onload'] = onload_preload_images( $preload ); // update preloading image list
    return $menu;
}
}

// ------------------------------------------------------------------------

/**
 * Makes a Vertical Menu
 *
 * Generates and manages preloaded images for a vertical menu.
 *
 * @access	public
 * @param	string	path to file
 * @param	string	path to images (no t)
 * @param	array	array[0..4] defining 'url','title','imageprefix',selected
 * @return	string
 */	
// Implements Macromedia-style Rollover Vertical Menu
//
// name: the prefix for the images where <name>_<imagename>_<link|hover>.png
// path: path to the image files (no trailing /)
// items: an array of arrays, where the interior array contains four items,
// 0..4 where 0=targeturl, 1=title_text, 2=imagename, 3="selected" (true/false)
//
// returns: menu array with three elements: menu['onload'], menu['body'] and menu['head']
if ( ! function_exists('make_menu_vert'))
{
function make_menu_vert( $name, $path, $items ) { global $menus;
    if ( count($items) == 0 || strlen($name) == 0 ) return NULL;
    $preload=array();
    $n=0; $output="";
    foreach( $items as $item ) {
        $n++;
//   <a href="#" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Image1','','file:///',1)"><img src="file:///" alt="Read the Blog" name="Image1" width="x" height="y" border="0" id="Image1" /></a>
        $output .= '<a href="' . $item[0] . '" title="' . $item[1]
                . '" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage(\''
                . $name . $n . '\', \'\', \'' . $path . '/' . $name . '_' . $item[2]
                . '_hover.png\',1)"><img src="' . $path . '/' . $name . '_' . $item[2]
                . (isset($item[3])&&$item[3]===true ?'_link.png' : '_hover.png')
                . '" name="' . $name . $n . '" border="0" id="' . $name . $n . '"></a>';
        $preload[] = $path . '/' . $name . '_' . $item[2] . '_hover.png';
    }
    $size= getimagesize($filler_image);
    $h = $size[1];
    $menu   = $output;
    $menus[$name]    = $menu;
    $menus['head']   = init_stylish_buttons();
    $menus['onload'] = onload_preload_images( $preload ); // update preloading image list
    return $menu;
}
}

// ------------------------------------------------------------------------

/**
 * Returns the menus global (or you can just reference it with "$global menus;")
 *
 * Generates and manages preloaded images for a horizontal menu.
 *
 * @access	public
 * @return	string
 */
if ( ! function_exists('get_menus'))
{
function get_menus() { global $menus; return $menus; }
}



?>