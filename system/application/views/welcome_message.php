<html>
<head>
<title>Welcome to HerbIgniter</title>

<style type="text/css">

body {
 background-color: #fff;
 margin: 40px;
 font-family: Lucida Grande, Verdana, Sans-serif;
 font-size: 14px;
 color: #4F5155;
}

a {
 color: #003399;
 background-color: transparent;
 font-weight: normal;
}

h1 {
 color: #444;
 background-color: transparent;
 border-bottom: 1px solid #D0D0D0;
 font-size: 16px;
 font-weight: bold;
 margin: 24px 0 2px 0;
 padding: 5px 0 6px 0;
}

code {
 font-family: Monaco, Verdana, Sans-serif;
 font-size: 12px;
 background-color: #f9f9f9;
 border: 1px solid #D0D0D0;
 color: #002166;
 display: block;
 margin: 14px 0 14px 0;
 padding: 12px 10px 12px 10px;
}

</style>
</head>
<body>

<h1>Welcome to HerbIgniter!</h1>

<p>The page you are looking at is being generated dynamically by HerbIgniter.</p>

<p>If you would like to edit this page you'll find it located at:</p>
<code>system/application/views/welcome_message.php</code>

<p>The corresponding controller for this page is found at:</p>
<code>system/application/controllers/welcome.php</code>

<p>If you are exploring HerbIgniter for the very first time, you should start by reading the <a href="HI_user_guide/">User Guide</a>.</p>

<p><b>System check:</b>
<?php
// Load Classes
//$this->load->library('benchmark'); messes up our time_elapsed if included twice
$this->load->library('calendar');
$this->load->library('cart');
$this->load->library('config');
$this->load->library('email');
$this->load->library('encrypt');
//$this->load->library('exceptions'); cannot load, already loaded
$this->load->library('form_validation');
$this->load->library('ftp');
$this->load->library('hooks');
$this->load->library('image_lib');
$this->load->library('input');
$this->load->library('language');
$this->load->library('loader');
$this->load->library('log');
$this->load->library('model');
//$this->load->library('output'); already included by default
//$this->load->library('page'); already included by default
$this->load->library('pagination');
$this->load->library('parser');
$this->load->library('profiler');
$this->load->library('router');
$this->load->library('session');
//$this->load->library('sha1'); says it doesn't exist..
$this->load->library('table');
$this->load->library('typography');
$this->load->library('unit_test');
$this->load->library('validation');
$this->load->library('website');
$this->load->library('xmlrpc');
$this->load->library('xmlrpcs');
$this->load->library('zip');
// Load Helpers
$this->load->helper('ajax');
$this->load->helper('array');
$this->load->helper('audio');
$this->load->helper('biz');
$this->load->helper('chart');
$this->load->helper('compatibility');
$this->load->helper('cookie');
$this->load->helper('country');
$this->load->helper('css');
$this->load->helper('data');
$this->load->helper('date');
$this->load->helper('directory');
$this->load->helper('download');
$this->load->helper('email');
$this->load->helper('file');
$this->load->helper('flash');
$this->load->helper('form');
$this->load->helper('geshi');
$this->load->helper('hash');
$this->load->helper('html');
$this->load->helper('inflector');
$this->load->helper('js');
$this->load->helper('language');
$this->load->helper('menu');
$this->load->helper('number');
$this->load->helper('path');
$this->load->helper('projax');
$this->load->helper('query');
$this->load->helper('recaptcha');
$this->load->helper('security');
$this->load->helper('smiley');
$this->load->helper('social');
$this->load->helper('string');
$this->load->helper('text');
$this->load->helper('typography');
$this->load->helper('url');
$this->load->helper('xml');
?> Complete.</p>

<p><br />Page rendered in {elapsed_time} seconds, with all libraries and helpers loaded.</p>

</body>
</html>