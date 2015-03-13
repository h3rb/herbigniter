<?php
// ReGeSH - used by geshi_helper.php in above directory for AJAX regeshifying
// (c) 2009 Gudagi - Part of HerbIgniter, not GeSHi
include_once 'geshi.php'; 

    $content = file_get_contents(urldecode($_GET['u']);
    $geshi = new GeSHi($content, $_GET['h']);
    echo urlencode($geshi->parse_code());

?>
