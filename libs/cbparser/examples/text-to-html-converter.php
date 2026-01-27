<?php
$version = '0.5';

/*
	simple text to html converter

	I threw this together primarily as an example for "how to use cbparser", my bbcode parser,
	and because I figured it would be easier than using corzblog to create simple html files;
	here we get the raw html back directly, saving one step.

	I use cbparser (the "my oh! how did you find it!" bbcode parser) for my text-2-html
	convertions anyway, and this wee gui is proving handy for me, might be handy for you, too.

	All the "extra" functions are covered, too,

	Obviously, you need cbparser.php bbcode parser somewhere to use this. get that here..

	http://corz.org/engine?download=menu&section=corz%20function%20library

	v0.4 spews the output back into the page inside <pre> tags, and leaves your textarea as-is
	so you can edit, etc. This also prevents &lt;, etc., being transformed into "<" in the textarea.

	;o) Cor

	(c) 2004->tomorrow! ~ cor + corz.org ;o)

	Please view the license for this free software, here..
	http://corz.org/free-scripts-licence.nfo

*/

// saves typing..
$htmlc_root = $_SERVER['DOCUMENT_ROOT'];

// You will need a local copy of this for IE7/8 users..
$HTML5_shiv = '/inc/js/html5.js';


// do your own thing here (include prefs)..
//require_once $htmlc_root.'/inc/init.php';

$header_file = '/inc/header.php';
$footer_file = '/inc/footer.php';


// include the bbcode parser, very important.
require_once '../cbparser.php';

$cbparser = new cbparser();

$cbparser->_blogzpath = '/blog/';
$cbparser->_mail_addy = 'me@example.com';
$cbparser->_buttons_dir = '../img/buttons/';
$cbparser->_smiley_folder = '../img/smileys/';
$cbparser->_js_funcs = '../js/func.js';

// code highlighting colors..
$cbparser->_highlight_string = '#DD0000';
$cbparser->_highlight_comment = '#FF8000';
$cbparser->_highlight_keyword = '#007700';
$cbparser->_highlight_bg = '#ffffff';
$cbparser->_highlight_default = '#0000BB';
$cbparser->_highlight_html = '#000000';



if (isset($_POST['cvrt-text'])) { $text = ($_POST['cvrt-text']); } else { $text = ''; }
if (get_magic_quotes_gpc()) { $text = stripslashes($text); }

//	you posted some text..
if (isset($_POST['preview'])) {

	//	this is the main line.
	//	this line is all you need for basic convertion jobs.
	$converted = $cbparser->bb2html($text);

	//	we can do some error checking, too..
	if ($converted == '') $converted = "tags don't balance!\n\nin other words, you have opened a tag, but not closed it, or something..\ngo back and try again!";

} else { $converted = ''; }

	echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, width=device-width" />
<title>convert text to html (HTML5) tags with the php text converter tool. (accepts bbcode)</title>';
if ($HTML5_shiv !== '') {
	echo '
<!--[if lt IE 9]><script src=',$HTML5_shiv,'></script><![endif]-->';
}
echo '
<meta name="description" content="convert text to html, convert bbcode to html, convert bbcode text to html, and more! you can do bbcode, too" />
<link rel="stylesheet" href="../style/original.css" type="text/css" media="screen" />
</head>
<body>
<div class="toplinks">
	you can run this at home..&nbsp;&nbsp;
	<a href="http://corz.org/engine?section=php&source=menu"
	title="download the source! use your own effin bandwidth!"><strong>source is available!</strong></a>
</div>';
if (isset($header_file)) {
	$header_file = str_replace($_SERVER['DOCUMENT_ROOT'], '', $header_file);
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$header_file)) {
		include $_SERVER['DOCUMENT_ROOT'].$header_file;
	}
}

echo '
<div class="content tool">
<div id="blogzcol">
	<article>
	<div class="blog-entry">
		<h1>bbcode capable text to html converter..</h1>
		<p>Convert text to html with this tool. Put plain text in, get HTML5 tags back..&nbsp;
		<small>(hint: you can use <a href="http://corz.org/blog/inc/cbparser.php"
		id="cbparser-bbcode-parser-link"title="this is just a wee gui for one of cbparser\'s functions"
		onclick="window.open(this.href); return false;">bbcode</a>)</small>';

if ($converted) {
	echo '
		<h3>Output:</h3>
		<pre class="code-block">';
	// rough tidy-up..
	$in = array("<br />", "\n\n");
	$out = array("<br />\n", "\n");
	echo $cbparser->switch_entities(str_replace($in, $out, $converted)),
		'</pre>
		<h3>Which would look like..</h3>';
}
$cbparser->do_bb_form($text, $converted, '', false, '', false, '', '', 'cvrt', true, false);

echo '
	</div>
	</div>
	<div class="clear-half"></div>
</div>';
if (isset($footer_file)) {
	$footer_file = str_replace($_SERVER['DOCUMENT_ROOT'], '', $footer_file);
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$footer_file)) {
		include $_SERVER['DOCUMENT_ROOT'].$footer_file;
	}
}
echo '
</body>
</html>';

?>