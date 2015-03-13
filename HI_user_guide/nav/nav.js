function create_menu(basepath)
{
	var base = "http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/";

	document.write(
		'<table cellpadding="0" cellspaceing="0" border="0" style="width:98%"><tr>' +
		'<td class="td" valign="top">' +

		'<ul>' +
		'<li><a href="'+base+'index.html" class="random-color">User Guide Home</a></li>' +	
		'<li><a href="'+base+'toc.html" class="random-color">Table of Contents + Quick Ref</a></li>' +
		'</ul>' +	

		'<h3>Basic Info</h3>' +
		'<ul>' +
			'<li><a href="'+base+'general/requirements.html" class="random-color">Server Requirements</a></li>' +
			'<li><a href="'+base+'license.html" class="random-color">License Agreement</a></li>' +
			'<li><a href="'+base+'changelog.html" class="random-color">Change Log</a></li>' +
			'<li><a href="'+base+'general/credits.html" class="random-color">Credits</a></li>' +
		'</ul>' +	
		
		'<h3>Installation</h3>' +
		'<ul>' +
			'<li><a href="http://gudagi.net/herbigniter/HerbIgniter_1.7.2.zip" class="random-color">Downloading HerbIgniter</a></li>' +
			'<li><a href="'+base+'installation/index.html" class="random-color">Installation Instructions</a></li>' +
			'<li><a href="'+base+'installation/upgrading.html" class="random-color">Upgrading from a Previous Version</a></li>' +
			'<li><a href="'+base+'installation/troubleshooting.html" class="random-color">Troubleshooting</a></li>' +
		'</ul>' +
		 
		'<h3>Introduction</h3>' +
		'<ul>' +
			'<li><a href="'+base+'overview/getting_started.html" class="random-color">Getting Started</a></li>' +
			'<li><a href="'+base+'overview/at_a_glance.html" class="random-color">HerbIgniter at a Glance</a></li>' +
			'<li><a href="'+base+'overview/cheatsheets.html" class="random-color">HerbIgniter Cheatsheets</a></li>' +
			'<li><a href="'+base+'overview/features.html" class="random-color">Supported Features</a></li>' +
			'<li><a href="'+base+'overview/appflow.html" class="random-color">Application Flow Chart</a></li>' +
			'<li><a href="'+base+'overview/mvc.html" class="random-color">Model-View-Object</a></li>' +
			'<li><a href="'+base+'overview/goals.html" class="random-color">Architectural Goals</a></li>' +
		'</ul>' +	

		'<h3>Additional Resources</h3>' +
		'<ul>' +
		'<li><a href="http://gudagi.net/herbigniter/freaky/">Freaky Discourse</a></li>' +
		'<li><a href="http://gudagi.net/herbigniter/bugs/">Bug Tracker</a></li>' +
		'</ul><br><br><br><a href="#">IMPORTANT: Docs are not accurate and will be updated Sunday, September 20, 2009</a>' +	
				
		'</td><td class="td_sep" valign="top">' +

		'<h3>General Topics</h3>' +
		'<ul>' +
			'<li><a href="'+base+'general/urls.html" class="random-color">HerbIgniter URLs</a></li>' +
			'<li><a href="'+base+'general/objects.html" class="random-color" title="Read this first!">Objects&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
			'<li><a href="'+base+'general/reserved_names.html" class="random-color">Reserved Names</a></li>' +
			'<li><a href="'+base+'general/views.html" class="random-color">Views</a></li>' +
			'<li><a href="'+base+'general/models.html" class="random-color">Models</a></li>' +
			'<li><a href="'+base+'general/helpers.html" class="random-color">Helpers</a></li>' +
			'<li><a href="'+base+'general/plugins.html" class="random-color">Plugins</a></li>' +
			'<li><a href="'+base+'general/libraries.html" class="random-color">Using HerbIgniter Libraries</a></li>' +
			'<li><a href="'+base+'general/creating_libraries.html" class="random-color">Creating Your Own Libraries</a></li>' +
			'<li><a href="'+base+'general/core_classes.html" class="random-color">Creating Core Classes</a></li>' +
			'<li><a href="'+base+'general/hooks.html" class="random-color">Hooks - Extending the Core</a></li>' +
			'<li><a href="'+base+'general/autoloader.html" class="random-color">Auto-loading Resources</a></li>' +
			'<li><a href="'+base+'general/common_functions.html" class="random-color">Common Functions</a></li>' +
			'<li><a href="'+base+'general/scaffolding.html" class="random-color">Scaffolding</a></li>' +
			'<li><a href="'+base+'general/routing.html" class="random-color">URI Routing</a></li>' +
			'<li><a href="'+base+'general/errors.html" class="random-color">Error Handling</a></li>' +
			'<li><a href="'+base+'general/caching.html" class="random-color">Caching</a></li>' +
			'<li><a href="'+base+'general/profiling.html" class="random-color">Profiling Your Application</a></li>' +
			'<li><a href="'+base+'general/managing_apps.html" class="random-color">Managing Applications</a></li>' +
			'<li><a href="'+base+'general/alternative_php.html" class="random-color">Alternative PHP Syntax</a></li>' +
			'<li><a href="'+base+'general/security.html" class="random-color">Security</a></li>' +
			'<li><a href="'+base+'general/styleguide.html" class="random-color">PHP Style Guide</a></li>' +
			'<li><a href="'+base+'doc_style/index.html" class="random-color">Writing Documentation</a></li>' +
		'</ul>' +
		
		'</td><td class="td_sep" valign="top">' +

				
		'<h3>Classes</h3>' +
		'<ul>' +
		'<li><a href="'+base+'libraries/benchmark.html" class="random-color">Benchmarking Class</a></li>' +
		'<li><a href="'+base+'libraries/calendar.html" class="random-color">Calendar Class</a></li>' +
		'<li><a href="'+base+'libraries/cart.html" class="random-color">Cart Class</a></li>' +
		'<li><a href="'+base+'libraries/config.html" class="random-color">Config Class</a></li>' +
		'<li><a href="'+base+'database/index.html" class="random-color">Database Class</a></li>' +
		'<li><a href="'+base+'libraries/email.html" class="random-color">Email Class</a></li>' +
		'<li><a href="'+base+'libraries/encryption.html" class="random-color">Encryption Class</a></li>' +
		'<li><a href="'+base+'libraries/file_uploading.html" class="random-color">File Uploading Class</a></li>' +
		'<li><a href="'+base+'libraries/form_validation.html" class="random-color">Form Validation Class</a></li>' +
		'<li><a href="'+base+'libraries/ftp.html" class="random-color">FTP Class</a></li>' +
		'<li><a href="'+base+'libraries/table.html" class="random-color">HTML Table Class</a></li>' +
		'<li><a href="'+base+'libraries/image_lib.html" class="random-color">Image Manipulation Class</a></li>' +		
		'<li><a href="'+base+'libraries/input.html" class="random-color">Input and Security Class</a></li>' +
		'<li><a href="'+base+'libraries/loader.html" class="random-color">Loader Class</a></li>' +
		'<li><a href="'+base+'libraries/language.html" class="random-color">Language Class</a></li>' +
		'<li><a href="'+base+'libraries/output.html" class="random-color">Output Class</a></li>' +
		'<li><a href="'+base+'libraries/page.html" class="random-color" title="New output management!">Page Class&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
		'<li><a href="'+base+'libraries/pagination.html" class="random-color">Pagination Class</a></li>' +
		'<li><a href="'+base+'libraries/sessions.html" class="random-color">Session Class</a></li>' +
		'<li><a href="'+base+'libraries/trackback.html" class="random-color">Trackback Class</a></li>' +
		'<li><a href="'+base+'libraries/parser.html" class="random-color">Template Parser Class</a></li>' +
		'<li><a href="'+base+'libraries/typography.html" class="random-color">Typography Class</a></li>' +		
		'<li><a href="'+base+'libraries/unit_testing.html" class="random-color">Unit Testing Class</a></li>' +
		'<li><a href="'+base+'libraries/uri.html" class="random-color">URI Class</a></li>' +
		'<li><a href="'+base+'libraries/user_agent.html" class="random-color">User Agent Class</a></li>' +
		'<li><a href="'+base+'libraries/xmlrpc.html" class="random-color">XML-RPC Class</a></li>' +
		'<li><a href="'+base+'libraries/zip.html" class="random-color">Zip Encoding Class</a></li>' +
		'</ul>' +

		'</td><td class="td_sep" valign="top">' +

		'<h3>Helpers</h3>' +
		'<ul>' +
'<li><a href="'+base+'helpers/ajax_helper.html" class="random-color" title="Ajax Integration">Ajax Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/array_helper.html" class="random-color">Array Helper</a></li>' +
'<li><a href="'+base+'helpers/audio_helper.html" class="random-color" title="Audio Support">Audio Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/compatibility_helper.html" class="random-color">Compatibility Helper</a></li>' +
'<li><a href="'+base+'helpers/cookie_helper.html" class="random-color" title="Additional Cookie Helpers">Cookie Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/country_helper.html" class="random-color" title="MaxMind(tm) Geolocation, Xavier Currency Exchange and Language">Country Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/css_helper.html" class="random-color" title="Functions for deploying CSS">CSS Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/data_helper.html" class="random-color" title="New PHP Datatype Manipulation Routines">Data Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/date_helper.html" class="random-color" title="New formatting routines">Date Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/directory_helper.html" class="random-color">Directory Helper</a></li>' +
'<li><a href="'+base+'helpers/download_helper.html" class="random-color">Download Helper</a></li>' +
'<li><a href="'+base+'helpers/email_helper.html" class="random-color">Email Helper</a></li>' +
'<li><a href="'+base+'helpers/file_helper.html" class="random-color" title="Atomic, truly concurrency-protected file routines for Linux!">File Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/form_helper.html" class="random-color">Form Helper</a></li>' +
'<li><a href="'+base+'helpers/data_helper.html" class="random-color" title="Flat-file Hash Code Generation and Management">Hash Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/html_helper.html" class="random-color" title="New additions!">HTML Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/inflector_helper.html" class="random-color">Inflector Helper</a></li>' +
'<li><a href="'+base+'helpers/js_helper.html" class="random-color" title="New javascript routines including William Zorn\'s VectorGraphics Library">JS Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/menu_helper.html" class="random-color" title="Enhanced pre-loaded rollover menus">Menu Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/number_helper.html" class="random-color" title="Now with bitvector support!">Number Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/path_helper.html" class="random-color">Path Helper</a></li>' +
'<li><a href="'+base+'helpers/projax_helper.html" class="random-color">Projax Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/query_helper.html" class="random-color" title="Gudagi\'s EZSQL Integration">Query Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/recaptcha_helper.html" class="random-color" title="Integration with leading bot security!">reCAPTCHA Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/security_helper.html" class="random-color" title="New updates and injection protection options!">Security Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/smiley_helper.html" class="random-color">Smiley Helper</a></li>' +
'<li><a href="'+base+'helpers/social_helper.html" class="random-color" title="Functions for Facebook and Google Analytics">Social Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/string_helper.html" class="random-color" title="New kid-friendly options!">String Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/text_helper.html" class="random-color" title="Generates lorem ipsem paragraphs, and much more!">Text Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/typography_helper.html" class="random-color">Typography Helper</a></li>' +
'<li><a href="'+base+'helpers/url_helper.html" class="random-color" title="Pass MySQL database objects through URLs!">URL Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
'<li><a href="'+base+'helpers/xml_helper.html" class="random-color" title="Newly added support for  and AJAX/XML!">XML Helper&nbsp;<img src="http://gudagi.net/herbigniter/HerbIgniter_1.7.2/HI_user_guide/images/flag.png"></a></li>' +
		'</ul>' +
		
		'</td></tr></table>');
}