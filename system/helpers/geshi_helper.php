<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright		Copyright 2009 (c) Gudagi 
 * @license		http://herbigniter.com/HI_user_guide/license.html
 * @link		http://herbigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter GeSHi Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Helpers
 * @author		Herb
 * @link		http://herbigniter.com/HI_user_guide/helpers/array_helper.html
 */

// ------------------------------------------------------------------------

// Include the GeSHi library//
if ( !function_exists( 'HI_GeSHi' ) ) {
function HI_GeSHi() {};
include_once 'geshi/geshi.php';
}
	/*
    $content = file_get_contents($file['location']);
    $geshi = new GeSHi($content, $_POST['highlight']);
    echo $geshi->parse_code();
	*/
if(!function_exists('init_geshi_select'))
{
function init_geshi_select( $select_id, $target_id, $jsid, $url, $params ) { $HI =& get_instance();
     $regesh_url= base_url().'/system/helpers/geshi/regesh.php';
     $content = file_get_contents( $url );
     return '<script type="text/javascript">
var '.$jsid.'_AA;
function '.$jsid.'(){
  ' . $jsid. '_AA='.$jsid.'_BB();
  if ('.$jsid.'_AA==null)
   {  alert ("Browser does not support HTTP Request, so clicking the button you clicked won\'t work.  Try something else."); return;   }
  var url="'.$regesh_url.'"
  var params = "'.$params.'&url='.urlencode($url).'&highlight=" + document.getElementById("'.$select_id.'").value;
  '.$jsid.'_AA.open("POST", url, true);

  //Send the proper header information along with the request
  '.$jsid.'_AA.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  '.$jsid.'_AA.setRequestHeader("Content-length", params.length);
  '.$jsid.'_AA.setRequestHeader("Connection", "close");

  '.$jsid.'_AA.onreadystatechange = function() {//Call a function when the state changes.
  	if('.$jsid.'_AA.readyState == 4 && '.$jsid.'_AA.status == 200) 
		document.getElementById("' . $target_id . '").innerHTML='.$jsid.'_AA.responseText;
  }
'.$jsid.'_AA.send(params);}     
function stateChanged() 
{   if ('.$jsid.'_AA.readyState==4 || '.$jsid.'_AA.readyState=="complete") { document.getElementById("'.$target_id.'").innerHTML=urldecode('.$jsid.'_AA.responseText); }    }
function '.$jsid.'_BB()
{ var xmlHttp=null;  try  {    xmlHttp=new XMLHttpRequest();  }  catch (e)  { 	try  {     xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");    }  catch (e)  {     xmlHttp=new ActiveXObject("Microsoft.XMLHTTP"); }  }
  return xmlHttp;
}</script>';	
}
}

/**
 * GeSHI select control
 * 
 * Returns code that populates a combo box with GeSHi options
 *
 * @access	public
 * @param	string	id and name of object
 * @param	string	javascript for onchange
 * @param	mixed
 * @return	mixed	depends on what the array contains
 */
if(!function_exists('geshi_select'))
{
function geshi_select( $id='', $js ='' ) {
    return '<select id="' . $id . '" name="' . $id . '" onchange="' . $js . '">
<option value="text" selected></option>
<option value="abap">ABAP</option>
<option value="actionscript">ActionScript</option>
<option value="actionscript3">ActionScript 3</option>
<option value="ada">Ada</option>
<option value="apache">Apache configuration</option>

<option value="applescript">AppleScript</option>
<option value="apt_sources">Apt sources</option>
<option value="asm">ASM</option>
<option value="asp">ASP</option>
<option value="autoit">AutoIt</option>
<option value="avisynth">AviSynth</option>

<option value="bash">Bash</option>
<option value="basic4gl">Basic4GL</option>
<option value="bf">Brainfuck</option>
<option value="blitzbasic">BlitzBasic</option>
<option value="bnf">bnf</option>
<option value="boo">Boo</option>

<option value="c">C</option>
<option value="c_mac">C (Mac)</option>
<option value="caddcl">CAD DCL</option>
<option value="cadlisp">CAD Lisp</option>
<option value="cfdg">CFDG</option>
<option value="cfm">ColdFusion</option>

<option value="cil">CIL</option>
<option value="cobol">COBOL</option>
<option value="cpp">C++</option>
<option value="cpp-qt" class="sublang">&nbsp;&nbsp;C++ (QT)</option>
<option value="csharp">C#</option>
<option value="css">CSS</option>

<option value="d">D</option>
<option value="delphi">Delphi</option>
<option value="diff">Diff</option>
<option value="div">DIV</option>
<option value="dos">DOS</option>
<option value="dot">dot</option>

<option value="eiffel">Eiffel</option>
<option value="email">eMail (mbox)</option>
<option value="fortran">Fortran</option>
<option value="freebasic">FreeBasic</option>
<option value="genero">genero</option>
<option value="gettext">GNU Gettext</option>

<option value="glsl">glSlang</option>
<option value="gml">GML</option>
<option value="gnuplot">Gnuplot</option>
<option value="groovy">Groovy</option>
<option value="haskell">Haskell</option>
<option value="hq9plus">HQ9+</option>

<option value="html">HTML</option>
<option value="html4strict">HTML 4 (Strict)</option>
<option value="idl">Uno Idl</option>
<option value="ini">INI</option>
<option value="inno">Inno</option>
<option value="intercal">INTERCAL</option>
<option value="io">Io</option>

<option value="java">Java</option>
<option value="java5">Java(TM) 2 Platform Standard Edition 5.0</option>
<option value="javascript">Javascript</option>
<option value="kixtart">KiXtart</option>
<option value="klonec">KLone C</option>
<option value="klonecpp">KLone C++</option>

<option value="latex">LaTeX</option>
<option value="lisp">Lisp</option>
<option value="lolcode">LOLcode</option>
<option value="lotusformulas">Lotus Notes @Formulas</option>
<option value="lotusscript">LotusScript</option>
<option value="lscript">LScript</option>

<option value="lua">Lua</option>
<option value="m68k">Motorola 68000 Assembler</option>
<option value="make">GNU make</option>
<option value="matlab">Matlab M</option>
<option value="mirc">mIRC Scripting</option>
<option value="mpasm">Microchip Assembler</option>

<option value="mxml">MXML</option>
<option value="mysql">MySQL</option>
<option value="nsis">NSIS</option>
<option value="objc">Objective-C</option>
<option value="ocaml">OCaml</option>
<option value="ocaml-brief" class="sublang">&nbsp;&nbsp;OCaml (brief)</option>

<option value="oobas">OpenOffice.org Basic</option>
<option value="oracle11">Oracle 11 SQL</option>
<option value="oracle8">Oracle 8 SQL</option>
<option value="pascal">Pascal</option>
<option value="per">per</option>
<option value="perl">Perl</option>

<option value="php">PHP</option>
<option value="php-php4">PHP4</option>
<option value="php-php5">PHP5</option>
<option value="php-brief" class="sublang">&nbsp;&nbsp;PHP (brief)</option>
<option value="pic16">PIC16</option>
<option value="pixelbender">Pixel Bender 1.0</option>
<option value="plsql">PL/SQL</option>
<option value="povray">POVRAY</option>

<option value="powershell">posh</option>
<option value="progress">Progress</option>
<option value="prolog">Prolog</option>
<option value="providex">ProvideX</option>
<option value="python">Python</option>
<option value="qbasic">QBasic/QuickBASIC</option>

<option value="qbasic">QBasic/QuickBASIC</option>
<option value="rails">Rails</option>
<option value="reg">Microsoft Registry</option>
<option value="robots">robots.txt</option>
<option value="ruby">Ruby</option>
<option value="sas">SAS</option>

<option value="scala">Scala</option>
<option value="scheme">Scheme</option>
<option value="scilab">SciLab</option>
<option value="sdlbasic">sdlBasic</option>
<option value="smalltalk">Smalltalk</option>
<option value="smarty">Smarty</option>

<option value="sql">SQL</option>
<option value="tcl">TCL</option>
<option value="teraterm">Tera Term Macro</option>
<option value="text">Text</option>
<option value="thinbasic">thinBasic</option>
<option value="tsql">T-SQL</option>

<option value="typoscript">TypoScript</option>
<option value="vb">Visual Basic</option>
<option value="vbnet">vb.net</option>
<option value="verilog">Verilog</option>
<option value="vhdl">VHDL</option>
<option value="vim">Vim Script</option>

<option value="visualfoxpro">Visual Fox Pro</option>
<option value="visualprolog">Visual Prolog</option>
<option value="whitespace">Whitespace</option>
<option value="whois">Whois Response</option>
<option value="winbatch">Winbatch</option>
<option value="xml">XML</option>

<option value="xorg_conf">Xorg configuration</option>
<option value="xpp">X++</option>
<option value="z80">ZiLOG Z80 Assembler</option>
</select>';
}
}

if(!function_exists('geshify'))
{
function geshify( $content, $highlight ) {
    $geshi = new GeSHi($content, $highlight);
    return $geshi->parse_code();
}
}

/* End of file geshi_helper.php */
/* Location: ./system/helpers/geshi_helper.php */