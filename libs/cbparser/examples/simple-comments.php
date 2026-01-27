<?php
$simple_comments['version'] = '0.7';

/*
	Simple Comments Example

	A simple "comments facility" example script for cbparser.

	I put this together as an example of "how to use cbparser", the corz.org
	bbcode parser. Output is saved to a "comments" file, which must be writeable
	by the server process for all this to work as expected.

	I've had more than one request for such an example, so here it is. It is
	rudimentary, and lacks most of the security and finesse of the corz.org
	comments facility; no editing, or deleting, or spell-checking, or decent
	error-checking, none of that good stuff; but it does provide you with a
	working example, a framework on which to build whatever comment facility you
	might dream up. Have fun!

	A few preferences and features have been added since its inception. Ho hum.

	Obviously, you need cbparser.php bbcode parser somewhere to use this.
	If you don't have it already, go here..

		http://corz.org/engine?download=menu&section=corz%20function%20library

	To use:

	*	Drop the files simple-comments.php & comments.htm into the "examples"
		folder of the cbparser distribution, if they aren't there already.

		For other scenarios, set your location preferences, below.

		If you want to include() this inside some other script/page, remember to
		set the correct location of cbparser, probably to a fixed path, e.g..

		$simple_comments['cbparser_location'] = $_SERVER['DOCUMENT_ROOT'].'/inc/cbparser.php'

		The comments files can remain next to the containing page, you can
		easily have multiple pages running from a single instance, all with
		separate comments. Add one line to your page, and you got comments..

			include 'path/to/this/script';

	*	Make the file "comments.htm", world-writeable (probably: chmod 777)

	*	Load simple-comments.php - or your containing page, if running this as
		an include() - into a web browser..

			e.g.  http://mysite.com/cbparser/examples/simple-comments.php

	*	Make comments!

	;o) Cor

	(c) 2004->tomorrow! ~ cor + corz.org ;o)

	Please view the license for this free software, here:

		http://corz.org/free-scripts-licence.nfo

*/



// ..:: Prefs ::..


// Your comments go in here..
//
$simple_comments['data_file'] = 'comments.htm';
//
//	IMPORTANT!!!
//	The above file must be made WORLD-WRITEABLE! (chmod 777, or use ftp client)

// Tip: you could use a name based on $_SERVER['SCRIPT_FILENAME'], and have
//		lots of unique comment even files within the same directory. e.g..
//		foo.php, foo_comments.htm, bar.php, bar_comments.htm, etc.


// cbparser location. so we can include the bbcode parser (crucial)..
//
$simple_comments['cbparser_location'] = '../cbparser.php';
//
// If running elsewhere, or as an include, it's probably best to plug-in the full
// server path to cbparser. See the usage notes at the top of this document.


// Style Sheet (either a relative path, or else a path relative to server root,
// i.e. a valid HREF)..
$simple_comments['css_location'] = '../style/original.css';



// Comment post headers & footers..
//
// This text is added to all comments written to the comment file.
// alter these to suit your desire/site's content/needs..

// NOTE:  if you want the "show only recent comments" feature to work, keep the
//		  <div class="comment">, which is used as a marker to split the file.

// Top (inserted directly before each comment) ..
$simple_comments['pre_post'] = "\n<div class=\"comment\">\n<strong><small>Posted on ".date('l dS \of F Y h:i:s A')."..</small></strong><br />\n<br />\n";
//
// Bottom (inserted directly after) ..	[if using an <hr tag, it's smart put a <br tag after it.]
$simple_comments['post_post'] = "<br />\n<br />\n".'<hr style="width:50%;text-align:left;float:left;" /><br /></div>'."\n";


// Recent Comments
//
// How many of the most recent comments to show on the page?
// Set this to 0 to show *all* comments.
//
$simple_comments['recent'] = 10;


// User switch
//
// Enables the user to switch between "show all comments" and "show only recent comments".
// Set to false to give them no links, and thereby, no choice in the matter.
//
$simple_comments['user_switch'] = true;


// You will need a copy of this if you plan to support IE7/8 users.
// otherwise leave blank (the default) or remove.
$simple_comments['HTML5_shiv'] = ''; // $simple_comments['HTML5_shiv'] = '/inc/js/html5.js


$simple_comments['site_header'] = '/inc/header.php';
$simple_comments['site_footer'] = '/inc/footer.php';


// ..:: End Prefs ::..




// init..
$simple_comments['converted'] = '';

// if called directly, disable embedded mode..
$simple_comments['embed'] = true;
if (realpath($_SERVER['SCRIPT_FILENAME']) == realpath(__FILE__)) { $simple_comments['embed'] = false; }


// You might want to load cbparser conditionally, when required..
// This script is bursting with so much potential improvement! ;o)

require_once $simple_comments['cbparser_location'];
$cbparser = new cbparser();
$cbparser->_spammer_file = '/inc/anti-hammer/lists/banned_spammer_post_strings.txt';

//$cbparser->_highlight_string = '#E53600';
//$cbparser->_highlight_comment = '#FFAD1D';
//$cbparser->_highlight_keyword = '#47A35E';
//$cbparser->_highlight_bg = '#ffffff';
//$cbparser->_highlight_default = '#3F6DAE';
//$cbparser->_highlight_html = '#0D3D0D';

$cbparser->_blogzpath = '/blog/';
$cbparser->_mail_addy = 'me@example.com';
$cbparser->_buttons_dir = '../img/buttons/';
$cbparser->_smiley_folder = '../img/smileys/';
$cbparser->_js_funcs = '../js/func.js';


$cbparser->_highlight_string =  '#E53600';
$cbparser->_highlight_comment = '#FFAD1D';
$cbparser->_highlight_keyword = '#47A35E';
$cbparser->_highlight_bg =  '#ffffff';
$cbparser->_highlight_default = '#3F6DAE';
$cbparser->_highlight_html = '#0D3D0D';



// check $_GET variables..
if (isset($_GET['show-all'])) { $simple_comments['recent'] = 0; }

// grab the $_POSTed text..
if (isset($_POST['cvrt-text'])) {
	$simple_comments['text'] = ($_POST['cvrt-text']); } else { $simple_comments['text'] = ''; }
if (get_magic_quotes_gpc()) { $simple_comments['text'] = stripslashes($simple_comments['text']); }

// They hit 'publish'. Write out the comment..
if (isset($_POST['publish'])) {

	// this line is all you need for basic text->xhtml conversions..
	$simple_comments['converted'] = $cbparser->bb2html($simple_comments['text']);

	if ($simple_comments['converted']) {

		// free safe write file function at the foot of this script!
		write_comment($simple_comments['data_file'], $simple_comments['pre_post'].$simple_comments['converted'].$simple_comments['post_post']);

		// load the page fresh, so user's post doesn't get re-posted on a page refresh.
		header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']); // you could add a "#section" here

		// reset these now..
		$simple_comments['converted'] = $simple_comments['text'] = '';
	}

//	preview some text ..
} else if (isset($_POST['preview'])) {

	// that cbparser call again..
	$simple_comments['converted'] = $cbparser->bb2html($simple_comments['text']);

	//	we can do some error checking, too..
	if ($simple_comments['converted'] == '') {
		if ($simple_comments['text'] == '') { // they posted an empty form..
			$simple_comments['converted'] = "<h3>You're firing blanks!<br />
<br />
Try again!</h3>";
		} else { // cbparser returned an empty string..
			$simple_comments['converted'] = "<h3>Tags don't balance!<br />
In other words, you have opened a tag, <br />
but not closed it, or something..<br />
<br />
Try again!</h3>";
		}
	} else { // cbparser returned some XHTML, on with the preview..
		$simple_comments['converted'] ='<div class="clear-tiny" id="preview"></div><h2>Preview..</h2><br />'.$simple_comments['converted'];
	}
}

if (!$simple_comments['embed']) { // in stand-alone mode, spit out an HTML page header..
	echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, width=device-width" />
<title>bbcode comments (cbparser example script, from corz.org)</title>
<meta name="description" content="simple comments example script, for cbparser, from corz.org. convert text to html and make comments, convert bbcode to html, convert bbcode text to html and more! actually, you can do bbcode, too" />';
if (isset($simple_comments['HTML5_shiv']) and !empty($simple_comments['HTML5_shiv'])) {
	echo '
<!--[if lt IE 9]><script src=',$simple_comments['HTML5_shiv'],'></script><![endif]-->';
}
$style_sheets = explode(',', $simple_comments['css_location']);
foreach ($style_sheets as $my_sheet) {
	echo '
<link rel="stylesheet" href="',$my_sheet,'" type="text/css" media="screen"/>';
}
echo '
</head>
<body>';
if (isset($simple_comments['site_header'])) {
	// normalize path..
	$simple_comments['site_header'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $simple_comments['site_header']);
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$simple_comments['site_header'])) {
		include $_SERVER['DOCUMENT_ROOT'].$simple_comments['site_header'];
	}
}
echo '
<div class="wide-content" style="margin:4em 1rem 1rem 4rem">
	<h1>comments..</h1>
'; // you will probably want to clean up these style statements.
}

if ($simple_comments['user_switch']) {
	if (isset($_GET['show-all'])) {
		echo '<p><a href="?show-recent" title="Show only recent comments" id="link-show-recent-comments">show only recent comments</a></p>';
	} else {
		echo '<p><a href="?show-all=true" title="Show ALL the comments" id="link-show-all-comments">show all comments</a></p>';
	}
} // else { echo '<p></p>'; } // if the shift in spacing bothers you, uncomment this.


// grab existing comments from the comment file..
$sce_comment_page = file_get_contents($simple_comments['data_file']);

// split the string into an array of "posts"..
$sce_comment_array = explode('<div class="comment">', $sce_comment_page);

// if showing recent comments only, and there are more posts than our desired limit..
if ($simple_comments['recent'] and (count($sce_comment_array) > $simple_comments['recent'])) {
	// slice the old posts (array elements) from the array..
	$sce_comment_array = array_slice($sce_comment_array, -$simple_comments['recent']);
	// glue back together..
	$sce_recent_comments = implode('<div class="comment">', $sce_comment_array);
	// the "glue" only goes *between* entries, so we add the first one back, manually..
	echo '<div class="comment">',$sce_recent_comments;
	// which is why we first check that the slice is required, else we'd have <div duplication and markup invalidation!
} else {

	// only a few comments (or showing all comments), forget the array and echo the string directly..
	echo $sce_comment_page;
}

// only when previewing, will there be anything here..
if (!empty($simple_comments['converted'])) { echo $simple_comments['converted']; }

echo '<br />
<br />
<h2 id="comments">leave a comment..</h2>
Put plain text in, write HTML tags to a file..&nbsp;<small>(hint: you can use <a href="http://corz.org/blog/inc/cbparser.php" id="cbparser-bbcode-parser-link"title="If you enjoy using this comment facility and funky bbcode, please consider a donation to their creator!" onclick="window.open(this.href); return false;">bbcode</a>)</small>
<div style="width: 70%">
<form class="cbform" id="cvrt" name="cvrt" method="post" action="',$_SERVER['SCRIPT_NAME'],'#preview">'; // set as wide as you'd like your comments column to be.

// print out the form..
$cbparser->do_bb_form($simple_comments['text'],'', '', false, '', false, '', '', 'cvrt', true, true, true);

echo '
</form>
</div>
<div class="clear-quarter"></div>
<div class="centered">
	<a href="http://corz.org/engine?section=php&amp;source=menu"
	title="download the source! use your own effin bandwidth!"><strong>source code is available!</strong></a>&nbsp;
</div>
<div class="clear-half"></div>'; // if you use this script on your site, please leave this bit in, ta.

if (!$simple_comments['embed']) {
	echo '
</div>';
	if (isset($simple_comments['site_footer'])) {
		// normalize path..
		$simple_comments['site_footer'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $simple_comments['site_footer']);
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$simple_comments['site_footer'])) {
			include $_SERVER['DOCUMENT_ROOT'].$simple_comments['site_footer'];
		}
	}
	echo '
</body>
</html>';
}


/*
function write_comment()

	Safely appends a comment to a flat file.
	Simply supply file path, and data string.
													*/
function write_comment($data_file, $data) {
	$fp = @fopen($data_file, 'ab'); // create file first, in case it's not
	if (is_writable($data_file)) {
		$lock = flock($fp, LOCK_EX);
		if ($lock) {
			fwrite($fp, $data);
			flock ($fp, LOCK_UN);
		} else { die("can't lock $data_file"); } // simultaneous posting? wow!
		fclose($fp);
		clearstatcache();
	} else { die("$data_file is not writable!"); } // chmod 777
}/*
end function write_comment()	*/


/*

	changes:

	0.7 Coded to utilize new cbparser class().



	0.6	Added variables for the javascript and css, to make it easier to use
		outside of the examples folder. I installed a copy of it on-site, too,
		as an example. (in the beta folder of the machine)

		Altered the recent/all switch slightly, so it now alternates between
		show-recent and sjow-all, rather than show-all and a bare "?". It still
		functions in exactly the same way, but won't get caught on anti-cracker
		.htaccess rules that look for requests ending in "?".

	0.5	Added automatic embedding, useful if you want to include() this script
		from within your own pages. Along these lines, I also improved the
		variable names, created a unique array to house the prefs, and so on.
		I added a test page to the distro, where you can see how easy this is
		to implement. Basically, you do nothing. include() and enjoy!

		If called directly, the script switches itself to old-school stand-
		alone mode.

	0.4	Added a link(s) where the user can switch between showing only recent
		comments or showing all comments, and a preference for you to enable/
		disable this functionality in the script, as required. It always loads
		initially with whatever number you have set for $simple_comments['recent'].

		Added more, clearer notes, and changes (this text), as well as a few
		minor cosmetic/style changes, as well as a few more php comments.

	0.3	Can now show only "so-many" of the most recent comments. This behaviour
		is optional and configurable (set to 0 to revert to all comments).

	0.2	Simple Comments Example now protects against refresh re-posting.

	0.1	First basic script, adapted from my earlier "text.converter.php" example
		script.
*/

?>