<?php	/* --- ۞---> text { encoding:utf-8;bom:no;linebreaks:unix;tabs:4sp; } */
if (realpath ($_SERVER['SCRIPT_FILENAME'])       ==      realpath (__FILE__))  {
				  usleep(1000000); header('Location: cbparser-demo.php'); die; }
/*

	cbparser.php	v2.0.2
	the corzblog bbcode to html and back to bbcode parser class

	Converts bbcode to html and back to bbcode, and does it quickly. A bit
	clunky, but it gets the job done each and every day. Output is 100% valid
	HTML5. All styling is in CSS.

	  NOTE:

		If you are upgrading, and something stops working as you expect, try
		reading the changes at the foot of this document.

		UPDATE: cbparser is now a class!

		PLEASE NOTE: THE DOCUMENTATION IS NOT UP TO DATE.

		SEE INCLUDED EXAMPLES FOR USAGE.


	Feel free to use this code for your own projects, I designed it with this in
	mind; linear. Leave a "corz.org" lying around somewhere. A link to my site
	is always cool.

	There's a full "ALL THE TAGS" reference here.. http://corz.org/bbtags and a
	smaller guide, "cbguide.php", which you can include under your forms as a
	quick refrence for users - see the cb_guide() method.


	These days, cbparser comes with a built-in front-end which you can access
	with the do_bb_form() function, perhaps something like this..

		do_bb_form($exmpl_str,'', '', false, '', false, '', '', 'blogform', false, true);

	See below for more information about the automatic gui creation.



	//*> Usage:

	Simply include this file somewhere in your php script and start a new
	instance of the cbparser class, like so..

		require $_SERVER['DOCUMENT_ROOT'].'/blog/inc/cbparser.php';
		$cbparser = new cbparser();

	or wherever you keep it. next, some string of text, probably from a $_POST
	variable, ie. a form..

		if (isset($_POST['form-text'])) { $my_string = $_POST['form-text']; }

	..is simply passed through one of cbparser's two functions..

		for bbcode to html conversion >>

			$my_string = $cbparser->bb2html($my_string, $title);

		for html to bbcode conversion >>

			$my_string = $cbparser->html2bb($my_string, $title);

		either can be simply ($my_string) if you don't require the extra unique
		entry functions, i.e. references.

	What comes back will be your string transformed into HTML or bbcode,
	depending on which direction you are going. If there was an error in your
	bbcode tags cbparser will return an empty string, so you can do some message
	for the user in that case. If cbparser recognises the poster as a spammer,
	it will return nothing. A global variable.. $this->_warning_message, will be
	available with the message "spammer". You can catch that, and also kill
	output at that point, or some other suitable action *hehe*

	cbparser doesn't care about errors in your HTML for the HTML>>bbcode
	conversion, it's main priority is to get "whatever the tags" back into an
	editable state.


	Notes:

	The second argument of the functions is the 'title', which corzblog supplies
	and uses for an html <div id="$title">, but you could provide from anywhere
	you like. Then we can do funky things unique to a particular entry, like
	individual references. see my blog, I use these a lot. My comments engine
	sets the <div id= from this too, allowing you/users to link directly to a
	particular comment. Groovy!

	if you don't need references that point to individual "id" entries, you can
	just ommit the second argument. it's a good feature, though. Worth a few
	quid in my PayPal account, I'd say. *g*

	If you add bits to the parser; complex stuff is better near the start. let
	me know if you add anything funky, or about any bugs, of course.


	Speed:

	My tests show even HUGE lists of str_replace statements are 'bloody fast'.
	there's a microtimer at the foot of my page, check yourself. I like this
	feature-filled approach a great deal, its linearity, and how easy it is to
	just plug stuff in. I hope you do to. I've certainly plugged in *a lot*!
	certainly worth a few quid in m- och forget it! heh. I've even added a few
	regex-type functions lately, once it's up and running, it's pretty fast.

	This very parser is responsible for all this..	http://corz.org/blog/
	well, I helped a bit.


	CSS rocks:

	We use css to style the various elements, mostly. the parser works fine
	without css, but you will probably want define a few styles. if you need
	guidance, see..

		http://corz.org/blog/inc/style/blog.css
	or..
		http://corz.org/inc/css/comments.css

	(call the files with different browsers for slightly differrent versions)

	If you "include" this file in your site header, you can call the parser's
	functions from anywhere onsite. it's tempting to use the phrase "parsing
	engine", but that accolade probably belongs to the PEAR package. As well as
	the parsing, and the built-in demo page, the one cbparser.php also handles
	"that comments bits" at the foot of most of my onsite tuts and contenty type
	pages.

	You get the idea.

	;o) Cor

	(c) 2003->tomorrow! cor + corz.org ;o)



	Extra Notes:

	InfiniTags™

		With cbparser's unique "InfiniTags™", users can make up bbcode tags
		on-the-fly. So.. Even though there is no [legend][/legend] tag, it will
		work just fine.

		cbparser will also translate < > into [ ] in the HTML >> BBCode
		translation. This isn't perfect, but close enough for rock 'n' roll. The
		most used tags are "built-in", but with InfiniTags™ you can create new
		bbcode as needed, and get the HTML back again, too! Real handy.



	Built-in GUI		[graphic user interface, aka. front-end]

		do_bb_form() parameters reference.

		cbparse can also create a gui for you, automatically.
		Simply call the do_bb_form() function with the following parameters..

		do_bb_form($textarea, $html_preview, $index, $do_title, $title, $do_pass, $hidden_post, $hidden_value,
												$form_id, $do_pre_butt, $do_pub_butt[, $nested] [, $ret_string])

		And they as follows..

		$textarea			:	the text you want to place in the textarea			[string]
		$html_preview		:	an html preview	(from bb2html() function)			[string] (use '' for no preview)
		$index				:	an optional numerical index for your form			[integer/integer as string]
		$do_title			:	do the title										[boolean]
		$title				:	an optional input for a title						[string, becomes input name/id]
		$do_pass			:	whether to create a password field or not			[boolean]
		$hidden_post		:	an optional hidden field (use to track a value)		[string, becomes input name/id]
								once set, it will remain set through previews, etc
		$hidden_value		:	the value of said hidden field						[string, defaults to 'true']
		$form_id			:	the main id for the form							[string]
		$do_pre_butt		:	whether to create a "preview" submit input.			[boolean]
		$do_pub_butt		:	whether to create a "publish" submit input.			[boolean]


		optional parameters..

		$nested	(false)			:	whether you are nested inside another form.			[boolean]
									if you are already inside a <form>, set to true.
									If you want cbparser to create the form for you, set to false (the default).

		$ret_string		(false)	:	Return as a string.										[boolean]
									Rather than echo output directly, return output as a string.

		example:
		here's the form cbparser uses for its own demo..

		do_bb_form($exmpl_str,'', '', false, '', false, '', '', 'blogform', true, false, false);

		note:
			the gui has some fairly nifty, and totally portable JavaScript
			functions (for example, you can click "bold" and the selected text
			will get [b]bold[/b] tags around it. Some of the other buttons are
			even niftier).

			these functions are provided by the "func.js" file which lives
			inside the "js" folder. you will probably need to edit the location
			of where you keep the js file (at the top of cbguide.php) and if you
			move it, you might want to edit the bottom of this file, for the
			bbcode demo, if you use that feature.

			If you know what a CSRF attack is, you may find the $hidden_value
			parameter most useful!

			The "preview" is the first submit button, interestingly, this will
			prevent most spam-bots from posting to your comments facility. my
			recently written "post-dumper" has been most useful in discovering
			these sort of things.


		Error Handling..

		you can check for errors by querying  $this->_state (or just $cb_state,
		from the global scope) which will return (odd numbers bad, even numbers
		good. zero best of all)..

			0 = no errors
			1 = tags don't balance
			2 = tags didn't balance, but were fixed
			3 = evil spammer
			5 = xss attack or php injection

		If cbparser has automatically fixed some tags,
		$GLOBALS['cbparser']['text'] (or $cbparser['text'], from the global
		scope) will contain the "fixed" text, and you will probably want to put
		that back into their textarea, as opposed to the raw, unfixed input from
		your input $_POST variables.

		In fact, $GLOBALS['cbparser']['text'] ALWAYS contains the bbcode text, so you may
		reliably use that for your textarea at all times. If any tags were
		closed, you can access *only* those tags, if you need to, via
		$this->_close_tags (or $cbparser['close_tags'] from the global scope),
		which might contain something like '[/i][/b]'.

*/





// let's get classy!
//
class cbparser {



	//*> Preferences..
	//


	//*> Blog Path..

	public $_blogzpath = '/blog/';	 //distro	public $_blogzpath = '/cbparser/';




	//*> cbguide..

	// handy buttons and controls for under your form..
	public $_use_cb_guide = true;


	/*
		Smileys for cbguide.
		Optional, but fun.

		Enter the full path to the smiley folder relative to your http root..
		Alternatively you can use a URL, like "http://domain.com/blog/inc/smileys/"
	*/
	public $_smiley_folder = '/blog/inc/smileys/';

	/*
		A nice idea is to use a mod_rewrite and create a permanent smiley
		location which can be moved around later. perhaps
		http://mysite/smileys/..

			RewriteRule ^smileys/(.*) /some/path/to/real/location/$1 [nc]

		then it doesn't matter where they *really* are, the link is always..
		/smileys/some.gif if you need to move the *actual* smiley folder in the
		future, you simply edit the rule.
	*/


	// Buttons for cbguide..
	// Enter the path to wherever you keep your buttons..
	public $_buttons_dir = '/blog/inc/img/buttons/';





	//*> Pajamas..

	/*
		Secure Login.

		As well as a regular password input for your form, the built-in GUI can
		also integrate with the pajamas authentication system..

			http://corz.org/server/security/pajamas.php

		If you want a secure php + javascript login system, check it out. Note:
		you must initialize your pajamas object as "$auth" for this to work
		straight off the bat, ie..

			$auth = new pajamas();		or..	$auth = new pajamas('MyUID');

	*/


	// Set this to true, false, or "inherit".
	// "inherit" will check to see if it is running inside a known app, and if
	// so, inherit its settings.

	public $_use_pajamas = 'inherit';




	//*> Spammers

	/*
		SPAMMERS!!

		If they want to place their casino link on your site, ask them to pay
		for it. If their hot casino tips are really so hot, a few quid shouldn't
		be a problem.

		If you set this to false just before calling the function for a
		"preview", you can do a "mock" output. To the spammer, it looks like
		their link will work just fine, but for the actual post, set it to
		true.. hahahah!

		Or else just set it to true here, and be done with it.
		*/

	public $_prevent_spam = true;



	/*
		So your page gets popular..

		Apart from the pesky casinos, you may find other spammers taking advantage
		of your nice comments facility, especially if you have high Google PR.
		Add any strings they use to this list and have them defeated!
	*/
//	// yes, I'll think we'll need a separate file!

	/*
		so your page gets *really* popular..

		We now use a seperate spammers file. good idea. easier to update.
		this file is a simple plain text list, one spammer string on each line
		and UNIX "\n" linebreaks. Make it so. e.g..

missy-elliott
Nice design good work !
neurontin
levaquin
restoril
poker black jack

		Enter the path to this file, from site root..
	*/
	public $_spammer_file = '/inc/anti-hammer/lists/banned_spammer_post_strings.txt';//distro	//$spammer_file = '/inc/data/spammers.txt';



	// Spammer User Agents..
	// list of known spammer agent strings, separated by commas.
	// it's okay to put spaces between the entries.
	public $_spammer_agents = 'AIRF, Indy Library';

	//2do.. use file.



	// in the event of a spammer, cbparser will return this string instead of the input text..
	public $_spammer_return_string = "<h2>&nbsp;&nbsp;spammer!</h2><br /><br />";





	//*> XSS/Security

	//2do.. incorporate my gd-verification (captcha) script, which essentially cuts spam to near zero.

	/*
		prevent xss (cross-site scripting) attacks

		This seems to be a hot topic among web developers. xss attacks can vary
		from annoying pop-ups planted by dodgy users, to cookie-theft and other
		nice stuff. If you run a site with sensitive user data, especially
		sensitive data in cookies, you'll probably want to enable this. Surely
		*you* see the comments first, though.
	*/
	public $_prevent_xss = true;





	//*> Email address..

	/*	now we can do mailto: URL's, like this.. [mmail=the big red thing]mail me![/mmail]
		"the big red thing" being the subject (you can use quotes, if you like)
		enter your email address here. it will be "mashed" to protect against spambots

		if you are running this inside corzblog, it will already have been set, there.
	*/
//	if (!isset($corzblog['mail_addy'])) $corzblog['mail_addy'] = 'corzblog@corz.org';	//distro	if (!isset($corzblog['mail_addy'])) $corzblog['mail_addy'] = 'me@myaddress.com';
	public $_mail_addy = 'me@myaddress.com';	//distro	if (!isset($corzblog['mail_addy'])) $corzblog['mail_addy'] = 'me@myaddress.com';

	/*	if you use cbparser in a "public" setting, (like site comments or something)
		there is now a regular email tag for them, too..

			[email="soso@email.com"]mail me![/email]

		Their address will also be "mashed". (curious? look at the HTML page source.)

			[email="soso@email.com?subject=yo!"]mail me![/email]

		would work fine.
		*/




	//*> Visual/Styles..


	// php syntax highlighting
	// for the cool colored code tags [ccc][/ccc]..
	public $_highlight_string = '#E53600';
	public $_highlight_comment = '#FFAD1D';
	public $_highlight_keyword = '#47A35E';
	public $_highlight_bg = '#FFFFFF';
	public $_highlight_default = '#3F6DAE';
	public $_highlight_html = '#0D3D0D';


	// note: you need to include the <?php tags to get the highlighting
	// cbguide's button thoughfully adds these for you.




	//*> Messages..

	/*
		you could do something like..

			if (!empty($cb_warning_message)) {
				echo'<span class="cb-notice">'.$cb_warning_message.'</span>';
			}

		my debug script pops up a nice dialog if it finds anything in this array
		but you can do whatever you like with it.
	*/


	/*
		the individual cbparser warning messages..
		note: it is possible to get more than one of these.

												*/
	public $_warnings_spammer = '<div class="centered" id="warning-message">spammer!</div>';

	public $_warnings_balance_fixed = '
		<div id="warning-message">
			<span class="warning">note</span>: some tags were automatically closed for you.<br />
			(check your bbcode)
		</div>';

	public $_warnings_imbalanced = '
		<div id="warning-message">
			<span class="warning">note</span>: your tags are not <a
			title="in other words; you have opened a tag, but not closed it.">balanced!</a><br />
			(check your bbcode)<br />
			<br />
		</div>
		<div id="bbinfo">
			<strong>notes..</strong><br />
			To produce a square bracket, double it! -&gt; <code><strong>[[ ! ]]</strong></code><br />
			To insert <strong><a href="http://corz.org/server/tricks/htaccess.php" title="tricks with Apache server" id="link-corz-htaccess-tutorials">.htaccess</a> code</strong>, shell code or ascii art, use <code><strong>[pre][/pre]</strong></code> tags.<br /><pre>
[pre]
RewriteRule (.*)\.htm $1.php [NC,R]
[/pre]</pre>
			For simple code statements, you can use <code><strong>[tt][/tt]</strong></code> tags.<br />
			For <strong>php</strong> web code, use <code><strong>[ccc]</strong></code> tags..<pre>
[ccc]&lt;?php
echo "foo!";
?&gt;[/ccc]</pre>
			For more information, check out <a href="http://corz.org/bbtags"
			onclick="window.open(this.href); return false;" title="ALL the tags!">the <span class="big">instructions</span></a>.<br />
			There is also a wee guide below the comment form.
		</div>';

	// probably your script should be catching this, but this is handy, anyway..
	public $_warnings_empty = '
		<div id="warning-message">
			there was no text!
		</div>';




	// stylesheets for the demo - separate multiple sheets with commas
	// this is in addition to the main blog styles..
	public $_styles = '/inc/css/main.css,/inc/css/comments.css,/inc/css/menu-multi.css';	// $this->_styles = '/inc/css/menu-small.css';



	// location of javascript functions..
	public $_js_funcs = '/blog/inc/js/func.js';



	//*> End prefs.




	// we will populate this..
	public $_text = '';


	// if there are any errors, they will be in here..
	var $_warning_message = '';


	var $_version = '2.0.1'; //


	// debugging prefs..
	// you shouldn't ever need to mess with these, but I'll leave them here, anyway.
	var $_check_tag_balance = true;
	var $_trans_warp_drive = false;
	// whatever you do, don't enable the trans-warp drive!


	var $_is_spammer = false;
	var $_spammer_strings = array();



	//*> Debugging..

	// set to true for debug output..
	var $_do_debug = false;

	// file to send debug output to.. If there is no / beginning the path, the
	// file location is assumed to be relative to this script.
	var $_debug_file = 'data/debug.out';

	// logging mode: 'append' or 'new' (anything not 'append' starts a new file).
	var $_degug_write_mode = 'new';

	// leave this blank.
	var $debug_string = '';


	// constructor..
	function __construct() {

		// init..
		$this->_spammer_strings = array();

		ini_set('highlight.string', $this->_highlight_string);
		ini_set('highlight.comment', $this->_highlight_comment);
		ini_set('highlight.keyword', $this->_highlight_keyword);
		ini_set('highlight.bg', $this->_highlight_bg);
		ini_set('highlight.default', $this->_highlight_default);
		ini_set('highlight.html', $this->_highlight_html);

		if ($this->_use_pajamas == 'inherit') {

			switch (true) {
				case isset($GLOBALS['corzblog']['use_pajamas']):
					$this->_use_pajamas = $GLOBALS['corzblog']['use_pajamas'];
					break;

				case isset($GLOBALS['comment_thing']['use_pajamas']):
					$this->_use_pajamas = $GLOBALS['comment_thing']['use_pajamas'];
					break;

				default:
					$this->_use_pajamas = false;
			}
		}

	}




	/*
		bbcocode to HTML

		converts bbcode to HTML5.

		usage:

			string ( string to transform [, string title])

					   */

	function bb2html() {

		$bb2html = func_get_arg(0);
if ($this->_do_debug) { $this->debug("\n".'INIT:: $bb2html::->'.$bb2html."<-\n\n"); }//:debug:
		if (func_num_args() == 2) {
			$title = func_get_arg(1);
			$id_title = $this->make_valid_id($title); // fix up bad id's
		} else {
			$id_title = $title = '';
		}

		// init.. [useful global array]
		$this->_state = 0;
		$this->_close_tags = '';
		$this->_text = $this->slash_it($bb2html);

		// oops!
		if ($bb2html == '') {
			$this->_state = 1;
			$this->_warning_message .= $this->_warnings_empty;
			return false;
		}

		// grab any *real* square brackets first, store 'em..
		$bb2html = str_replace('[[[[', '**$@$**[[', $bb2html); // catch demo tags next to demo tags
		$bb2html = str_replace(']]]]', ']]**@^@**', $bb2html); // ditto
		$bb2html = str_replace('[[[', '**$@$**[', $bb2html); // catch tags next to demo tags
		$bb2html = str_replace(']]]', ']**@^@**', $bb2html); // ditto
		$bb2html = str_replace('[[', '**$@$**', $bb2html); // finally!
		$bb2html = str_replace(']]', '**@^@**', $bb2html);


		/*
			pre-formatted text

			even bbcode inside [pre] text will remain untouched, as it should be.
			there may be multiple [pre] or [ccc] blocks, so we grab them all and create arrays..
			*/

		// because lowering the case of all tags before [pre] tags will lower the case of,
		// for example, .htaccess mod_rewrite flags..
		$bb2html = str_replace('PRE]', 'pre]', $bb2html);
		$pre = array(); $i = 9999;
		while ($pre_str = stristr($bb2html, '[pre]')) {
			$pre_str = substr($pre_str, 0, strpos($pre_str, '[/pre]') + 6);
			$bb2html = str_replace($pre_str, "***pre_string***$i", $bb2html);
			$pre[$i] = $this->encode(str_replace(array('**$@$**', '**@^@**'), array('[[', ']]'), $pre_str));
			$i++; //	^^	we encode this, for html tags, etc.
		}

		/*
			syntax highlighting (Cool Colored Code™)
			och, why not!
			*/
		$bb2html = str_replace('CCC]', 'ccc]', $bb2html);
		$ccc = array(); $i = 0;
		while ($ccc_str = stristr($bb2html, '[ccc]')) {
			$ccc_str = substr($ccc_str, 0, strpos($ccc_str, '[/ccc]') + 6);
			$bb2html = str_replace($ccc_str, "***ccc_string***$i", $bb2html);
			$ccc[$i] = str_replace(array('**$@$**', '**@^@**', "\r\n"), array('[[', ']]', "\n"), $ccc_str);
			$i++;
		}

		// ensure all bbcode is lowercase..
		$bb2html = $this->bbcode_to_lower($bb2html);

		// rudimentary tag balance checking..
		if ($this->_check_tag_balance) { $bb2html = $this->check_balance($bb2html); }
		if ($this->_state == 1)  { return false; } // imbalanced tags


		// xss attack prevention [99.9% safe!]..
//		if ($this->_prevent_xss) { $bb2html = $this->xssclean($bb2html); }

		// generic entity encode
//		$bb2html = htmlentities($bb2html, ENT_NOQUOTES | ENT_SUBSTITUTE, 'utf-8');	//2do.. check for php 5.4 first!
//		$bb2html = htmlentities($bb2html, ENT_NOQUOTES, 'utf-8');
		$bb2html = $this->switch_entities($bb2html);

		$bb2html = str_replace('[sp]', '&nbsp;', $bb2html);


		// process links?
		$this->_is_spammer = false;
		$bb2html = $this->process_links($bb2html);

		//	no tinned pidgeon!! (you probably have to be Scottish to understand this joke)
		if ($this->_prevent_spam and $this->_is_spammer) {
			$this->_state = 3;
			$this->_warning_message .= $this->_warnings_spammer;
			$this->_text = '';
			//return false; // zero-tolerance!
			return $this->_spammer_return_string; // zero-tolerance!
		}

		// the bbcode proper..

		// news headline block
		$bb2html = str_replace('[news]', '<div class="cb-news">', $bb2html);
		$bb2html = str_replace('[/news]', '<!--news--></div>', $bb2html);

		// references - we need to create the whole string first, for the str_replace
		$r1 = '<a class="cb-refs-title" href="#refs-'.$id_title.'" title="go to the reference">';
		$bb2html = str_replace('[ref]', $r1 , $bb2html);
		$bb2html = str_replace('[/ref]', '<!--ref--></a>', $bb2html);
		$ref_start = '<div class="cb-ref" id="refs-'.$id_title.'">
<a class="ref-title" title="back to the text" href="javascript:history.go(-1)">references:</a>
<div class="reftext">';
		$bb2html = str_replace('[reftxt]', $ref_start , $bb2html);
		$bb2html = str_replace('[/reftxt]', '<!--reftxt-->
</div>
</div>', $bb2html);

		// ordinary transformations..

		// we rely on the browser producing \r\n (DOS) carriage returns, as per spec.
		$bb2html = str_replace("\r",'<br />', $bb2html);		// the \n remains, and makes the raw html readable
		$bb2html = str_replace('[b]', '<strong>', $bb2html);	// ie. "\r\n" becomes "<br />\n"
		$bb2html = str_replace('[/b]', '</strong>', $bb2html);
		$bb2html = str_replace('[i]', '<em>', $bb2html);
		$bb2html = str_replace('[/i]', '</em>', $bb2html);
		$bb2html = str_replace('[u]', '<span class="underline">', $bb2html);
		$bb2html = str_replace('[/u]', '<!--u--></span>', $bb2html);
		$bb2html = str_replace('[big]', '<!--big--><span class="big">', $bb2html);
		$bb2html = str_replace('[/big]', '<!--big--></span>', $bb2html);
		$bb2html = str_replace('[sm]', '<small>', $bb2html);
		$bb2html = str_replace('[/sm]', '</small>', $bb2html);

		// tables (couldn't resist this, too handy)
		$bb2html = str_replace('[t]', '<div class="cb-table">', $bb2html);
		$bb2html = str_replace('[bt]', '<div class="cb-table-b">', $bb2html);
		$bb2html = str_replace('[st]', '<div class="cb-table-s">', $bb2html);
		$bb2html = str_replace('[/t]', '<!--table--></div><div class="clear"></div>', $bb2html);
		$bb2html = str_replace('[c]', '<div class="cell">', $bb2html);	// regular 50% width
		$bb2html = str_replace('[c2]', '<div class="cell">', $bb2html);	// in-case they do an intuitive thang
		$bb2html = str_replace('[c1]', '<div class="cell1">', $bb2html);	// cell data 100% width
		$bb2html = str_replace('[c3]', '<div class="cell3">', $bb2html);
		$bb2html = str_replace('[c4]', '<div class="cell4">', $bb2html);
		$bb2html = str_replace('[c5]', '<div class="cell5">', $bb2html);
		$bb2html = str_replace('[/c]', '<!--end-cell--></div>', $bb2html);
		$bb2html = str_replace('[r]', '<div class="cb-tablerow">', $bb2html);	// a row
		$bb2html = str_replace('[/r]', '<!--row--></div>', $bb2html);

		// admin replies..
		$bb2html = str_replace('[corz]', '<div class="corz-reply">', $bb2html);
		$bb2html = str_replace('[/corz]', '<!--corz--></div>', $bb2html);

		$bb2html = str_replace('[box]', '<span class="box">', $bb2html);
		$bb2html = str_replace('[/box]', '<!--box--></span>', $bb2html);
		$bb2html = str_replace('[bbox]', '<div class="box">', $bb2html);
		$bb2html = str_replace('[/bbox]', '<!--box--></div>', $bb2html);

		// simple lists..
		$bb2html = str_replace('[*]', '<li>', $bb2html);
		$bb2html = str_replace('[/*]', '</li>', $bb2html);
		$bb2html = str_replace('[ul]', '<ul>', $bb2html);
		$bb2html = str_replace('[/ul]', '</ul>', $bb2html);
		$bb2html = str_replace('[list]', '<ul>', $bb2html);
		$bb2html = str_replace('[/list]', '</ul>', $bb2html);
		$bb2html = str_replace('[ol]', '<ol>', $bb2html);
		$bb2html = str_replace('[/ol]', '</ol>', $bb2html);

		// fix up gaps..
		$bb2html = str_replace('</li><br />', '</li>', $bb2html);
		$bb2html = str_replace('<ul><br />', '<ul>', $bb2html);
		$bb2html = str_replace('</ul><br />', '</ul>', $bb2html);
		$bb2html = str_replace('<ol><br />', '<ol>', $bb2html);
		$bb2html = str_replace('</ol><br />', '</ol>', $bb2html);


		// smileys..
		//if (file_exists($_SERVER['DOCUMENT_ROOT'].$this->_smiley_folder)) {
			$bb2html = str_replace(':lol:', '<img alt="smiley for :lol:" title=":lol:" src="'
			.$this->_smiley_folder.'lol.gif" />', $bb2html);
			$bb2html = str_replace(':ken:', '<img alt="smiley for :ken:" title=":ken:" src="'
			.$this->_smiley_folder.'ken.gif" />', $bb2html);
			$bb2html = str_replace(':evil:', '<img alt="smiley for :evil:" title=":evil:" src="'
			.$this->_smiley_folder.'evil.gif" />', $bb2html);
			$bb2html = str_replace(':D', '<img alt="smiley for :D" title=":D" src="'
			.$this->_smiley_folder.'grin.gif" />', $bb2html);
			$bb2html = str_replace(':)', '<img alt="smiley for :)" title=":)" src="'
			.$this->_smiley_folder.'smile.gif" />', $bb2html);
			$bb2html = str_replace(';)', '<img alt="smiley for ;)" title=";)" src="'
			.$this->_smiley_folder.'wink.gif" />', $bb2html);
			$bb2html = str_replace(':eek:', '<img alt="smiley for :eek:" title=":eek:" src="'
			.$this->_smiley_folder.'eek.gif" />', $bb2html);
			$bb2html = str_replace(':geek:', '<img alt="smiley for :geek:" title=":geek:" src="'
			.$this->_smiley_folder.'geek.gif" />', $bb2html);
			$bb2html = str_replace(':roll:', '<img alt="smiley for :roll:" title=":roll:" src="'
			.$this->_smiley_folder.'roll.gif" />', $bb2html);
			$bb2html = str_replace(':erm:', '<img alt="smiley for :erm:" title=":erm:" src="'
			.$this->_smiley_folder.'erm.gif" />', $bb2html);
			$bb2html = str_replace(':cool:', '<img alt="smiley for :cool:" title=":cool:" src="'
			.$this->_smiley_folder.'cool.gif" />', $bb2html);
			$bb2html = str_replace(':blank:', '<img alt="smiley for :blank:" title=":blank:" src="'
			.$this->_smiley_folder.'blank.gif" />', $bb2html);
			$bb2html = str_replace(':idea:', '<img alt="smiley for :idea:" title=":idea:" src="'
			.$this->_smiley_folder.'idea.gif" />', $bb2html);
			$bb2html = str_replace(':ehh:', '<img alt="smiley for :ehh:" title=":ehh:" src="'
			.$this->_smiley_folder.'ehh.gif" />', $bb2html);
			$bb2html = str_replace(':aargh:', '<img alt="smiley for :aargh:" title=":aargh:" src="'
			.$this->_smiley_folder.'aargh.gif" />', $bb2html);
			$bb2html = str_replace(':-[', '<img alt="smiley for :-[" title=":-[" src="'
			.$this->_smiley_folder.'embarassed.gif" />', $bb2html);
		//}

		// anchors and stuff..
		$bb2html = str_replace('[img]', '<img class="cb-img" src="', $bb2html);
		$bb2html = str_replace('[/img]', '" alt="an image" />', $bb2html);

		// encode the URI part? //:2do.
		// what about custom alt tags. hmm. //:2do.


		// clickable mail URL ..
		$bb2html = preg_replace_callback("/\[lmail\=(.+?)\](.+?)\[\/lmail\]/i", array(&$this, 'create_lmail'), $bb2html);
		$bb2html = preg_replace_callback("/\[mmail\=(.+?)\](.+?)\[\/mmail\]/i", array(&$this, 'create_mmail'), $bb2html);
		$bb2html = preg_replace_callback("/\[email\=(.+?)\](.+?)\[\/email\]/i", array(&$this, 'create_mail'), $bb2html);

		// other URLs..
		$bb2html = str_replace('[url]', '<br /><br /><div class="warning">please check your URL bbcode syntax!!!</div><br /><br />', $bb2html);
		$bb2html = str_replace('[eurl=', '<a class="eurl" onclick="window.open(this.href); return false;" href=', $bb2html);
		$bb2html = str_replace('[turl=', '<a class="turl" title=', $bb2html); /* title-only url */
		$bb2html = str_replace('[purl=', '[url=', $bb2html); /* on-page anchor #so-and-so */
		$bb2html = str_replace('[url=', '<a class="url" href=', $bb2html);
		$bb2html = str_replace('[/url]', '<!--url--></a>', $bb2html);
		// encode the URI part? //:2do.
		// check for spammer strings in URL right here //:2do.

		// floaters..
		$bb2html = str_replace('[right]', '<div class="right">', $bb2html);
		$bb2html = str_replace('[/right]', '<!--right--></div>', $bb2html);
		$bb2html = str_replace('[left]', '<div class="left">', $bb2html);
		$bb2html = str_replace('[/left]', '<!--left--></div>', $bb2html);

		// code
		$bb2html = str_replace('[tt]', '<span class="teletype">', $bb2html);
		$bb2html = str_replace('[/tt]', '<!--tt--></span>', $bb2html);
		$bb2html = str_replace('[code]', '<span class="code">', $bb2html);
		$bb2html = str_replace('[/code]', '<!--code--></span>', $bb2html);
		$bb2html = str_replace('[code-block]', '<div class="code-block">', $bb2html);
		$bb2html = str_replace('[/code-block]', '<!--code-block--></div>', $bb2html);
		$bb2html = str_replace('[coderz]', '<div class="code-block">', $bb2html); // deprectated 2013 - remove 2015
		$bb2html = str_replace('[/coderz]', '<!--code-block--></div>', $bb2html);

		// new (cute) quotes..
		$bb2html = str_replace('[quote]', '<div class="quote">', $bb2html);
		$bb2html = str_replace('[/quote]', '<!--quote--></div>', $bb2html);

		// divisions..
		$bb2html = str_replace('[hr]', '<hr class="cb-hr" />', $bb2html);
		$bb2html = str_replace('[hr2]', '<hr class="cb-hr2" />', $bb2html);
		$bb2html = str_replace('[hr3]', '<hr class="cb-hr3" />', $bb2html);
		$bb2html = str_replace('[hr4]', '<hr class="cb-hr4" />', $bb2html);
		$bb2html = str_replace('[hrr]', '<hr class="cb-hr-regular" />', $bb2html);
		$bb2html = str_replace('[block]', '<blockquote><div class="blockquote">', $bb2html);
		$bb2html = str_replace('[/block]', '</div></blockquote>', $bb2html);
		$bb2html = str_replace('</div></blockquote><br />', '</div></blockquote>', $bb2html);

		// roll-over spoiler tag (to enable this, you need to make some css & add a spoiler image- see/borrow my example)
		$bb2html = str_replace('[spoiler]', '<span class="spoiler" onmouseover="this.className=\'spoiler hover\'" onmouseout="this.className=\'spoiler\'"><span>', $bb2html);
		$bb2html = str_replace('[/spoiler]', '</span></span>', $bb2html);

		$bb2html = str_replace('[big-spoiler]', '<div class="spoiler" onmouseover="this.className=\'spoiler hover\'" onmouseout="this.className=\'spoiler\'"><div>', $bb2html);
		$bb2html = str_replace('[/big-spoiler]', '</div></div>', $bb2html);

		$bb2html = str_replace('[block-mid]', '<div class="cb-block-center">', $bb2html);
		$bb2html = str_replace('[/block-mid]', '<!--block-mid--></div>', $bb2html);

		$bb2html = str_replace('[mid]', '<div class="cb-center">', $bb2html);
		$bb2html = str_replace('[/mid]', '<!--mid--></div>', $bb2html);


		// dropcaps. five flavours, small up to large.. [dc1]I[/dc] -> [dc5]W[/dc]
		$bb2html = str_replace('[dc1]', '<span class="dropcap1">', $bb2html);
		$bb2html = str_replace('[dc2]', '<span class="dropcap2">', $bb2html);
		$bb2html = str_replace('[dc3]', '<span class="dropcap3">', $bb2html);
		$bb2html = str_replace('[dc4]', '<span class="dropcap4">', $bb2html);
		$bb2html = str_replace('[dc5]', '<span class="dropcap5">', $bb2html);
		$bb2html = str_replace('[/dc]', '<!--dc--></span>', $bb2html);

		$bb2html = str_replace('[h2]', '<h2>', $bb2html);
		$bb2html = str_replace('[/h2]', '</h2>', $bb2html);
		$bb2html = str_replace('[h3]', '<h3>', $bb2html);
		$bb2html = str_replace('[/h3]', '</h3>', $bb2html);
		$bb2html = str_replace('[h4]', '<h4>', $bb2html);
		$bb2html = str_replace('[/h4]', '</h4>', $bb2html);
		$bb2html = str_replace('[h5]', '<h5>', $bb2html);
		$bb2html = str_replace('[/h5]', '</h5>', $bb2html);
		$bb2html = str_replace('[h6]', '<h6>', $bb2html);
		$bb2html = str_replace('[/h6]', '</h6>', $bb2html);

		// fix up input spacings..
		$bb2html = str_replace('</h2><br />', '</h2>', $bb2html);
		$bb2html = str_replace('</h3><br />', '</h3>', $bb2html);
		$bb2html = str_replace('</h4><br />', '</h4>', $bb2html);
		$bb2html = str_replace('</h5><br />', '</h5>', $bb2html);
		$bb2html = str_replace('</h6><br />', '</h6>', $bb2html);

		// oh, all right then..
		// TEST My [color=red]colour[/color] [color=rgba(31,42,254,0.67)]test[/color] [color=#C5BB41]test[/color]
	//	$bb2html = preg_replace('/\[color\=(.+?)\](.+?)\[\/color\]/is', "<span style=\"color:$1\">$2<!--color--></span>", $bb2html);
		$bb2html = preg_replace_callback('/\[color\=(.+?)\](.+?)\[\/color\]/is', array(&$this, 'get_color_tokens'), $bb2html);

		// I noticed someone trying to do these at the org. use standard pixel sizes
		function bbcode_size_to_css($size) {
			$size = (float)$size;
			if ($size >= 7) return '3em';      // XXL
			if ($size >= 6) return '2.5em';    // XL  
			if ($size >= 5) return '2em';      // Large (untuk [size=5])
			if ($size >= 4) return '1.5em';    // Medium
			if ($size >= 3) return '1.2em';    // Normal+
			if ($size >= 2) return '1em';      // Normal
			if ($size >= 1) return '0.9em';    // Small
			return '0.8em';                    // Tiny
		}
		$bb2html = preg_replace_callback(
			'/\[size\=(.+?)\](.+?)\[\/size\]/is', 
			function($matches) {
				$size_val = $matches[1];
				$content = $matches[2];
				$css_size = bbcode_size_to_css($size_val);
				return "<span style=\"font-size: {$css_size};\">$content<!--size--></span>";
			}, 
			$bb2html
		);

		// for URL's, and InfiniTags™..
		$bb2html = str_replace('[', ' <', $bb2html); // you can just replace < and >  with [ and ] in your bbcode
		$bb2html = str_replace(']', ' >', $bb2html); // for instance, [strike] cool [/strike] would work!
		$bb2html = str_replace('/ >', '/>', $bb2html); // self-closers
		$bb2html = str_replace('-- >', '-->', $bb2html); // close comments

		// get back any real square brackets..
		$bb2html = str_replace('**$@$**', '[', $bb2html);
		$bb2html = str_replace('**@^@**', ']', $bb2html);

		// prevent some twat running arbitary php commands on our web server
		// I may roll this into the xss prevention and just keep it all enabled. hmm.
		$php_str = $bb2html;
		$bb2html = preg_replace("/<\?(.*)\? ?>/is", "<strong>script-kiddie prank: &lt;?\\1 ?&gt;</strong>", $bb2html);
		if ($php_str != $bb2html) { $this->_state = 5; }

		// re-insert the preformatted text blocks..
		$cp = count($pre) + 9998;
		for ($i=9999;$i <= $cp;$i++) {
			$bb2html = str_replace("***pre_string***$i", '<pre>'.$pre[$i].'</pre>', $bb2html);
		}


		// re-insert the cool colored code..
		// we fix-up the output, too, make it HTML5.
		$cp = count($ccc) - 1;
		for ($i=0 ; $i <= $cp ; $i++) {
			$tmp_str = substr($ccc[$i], 5, -6);
	//		$tmp_str = highlight_string(stripslashes($tmp_str), true);
			if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) $tmp_str = stripslashes($tmp_str);
			$tmp_str = highlight_string($tmp_str, true);
			$tmp_str = str_replace('font color="', 'span style="color:', $tmp_str);
			$tmp_str = str_replace('font', 'span', $tmp_str); // erm.
			if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) $tmp_str = addslashes($tmp_str);
			$bb2html = str_replace("***ccc_string***$i", '<div class="cb-ccc">'.$tmp_str.'<!--ccccode--></div>', $bb2html);
		}


		$bb2html = $this->slash_it($bb2html);
		if ($this->_trans_warp_drive) { $this->_text = strrev($this->_text); }

if ($this->_do_debug) { $this->debug("\n".'FINAL:: $bb2html::->'.$bb2html."<-\n\n"); }//:debug:
if ($this->_do_debug) { $this->debug('out'); }
		return $bb2html;

	}/* end function bb2html()
	*/





	/*
	function html2bb()   */

	function html2bb() {

	  if (func_num_args() == 2) { $id_title = func_get_arg(1); } else { $id_title = ''; }
	  $html2bb = func_get_arg(0);
if ($this->_do_debug) { $this->debug("\n".'INIT:: $html2bb::->'.$html2bb."<-\n\n"); }//:debug:

		// we presume..
		$this->_state = 0;

		// pre-formatted text
		$pre = array();$i=9999;
		while ($pre_str = stristr($html2bb,'<pre>')) {
			$pre_str = substr($pre_str,0,strpos($pre_str,'</pre>')+6);
			$html2bb = str_replace($pre_str, "***pre_string***$i", $html2bb);
			$pre[$i] = str_replace("\n","\r\n",$pre_str);
			$i++;
		}

		// cool colored code
		$ccc = array();$i=0;
		while ($ccc_str = stristr($html2bb,'<div class="cb-ccc">')) {
			$ccc_str = substr($ccc_str,0,strpos($ccc_str,'<!--ccccode--></div>')+20);
			$html2bb = str_replace($ccc_str, "***ccc_string***$i", $html2bb);
			$ccc[$i] = str_replace("<br />","\r\n",$ccc_str);
			$ccc[$i] = str_replace("&nbsp;"," ",$ccc[$i]);
			$i++;
		}

		$html2bb = str_replace('[', '***^***', $html2bb);
		$html2bb = str_replace(']', '**@^@**', $html2bb);

		// news
		$html2bb = str_replace('<div class="cb-news">', '[news]', $html2bb);
		$html2bb = str_replace('<!--news--></div>', '[/news]', $html2bb);

		// references..
		$r1 = '<a class="cb-refs-title" href="#refs-'.$id_title.'" title="go to the reference">';
		$html2bb = str_replace($r1, "[ref]", $html2bb);
		$html2bb = str_replace('<!--ref--></a>', '[/ref]', $html2bb);
		$ref_start = '<div class="cb-ref" id="refs-'.$id_title.'">
<a class="ref-title" title="back to the text" href="javascript:history.go(-1)">references:</a>
<div class="reftext">';
		$html2bb = str_replace($ref_start, '[reftxt]', $html2bb);
		$html2bb = str_replace('<!--reftxt-->
</div>
</div>', '[/reftxt]', $html2bb);

		// let's remove all the linefeeds, unix
		$html2bb = str_replace(chr(10), '', $html2bb); //		"\n"
		// and Mac (windoze uses both)
		$html2bb = str_replace(chr(13), '', $html2bb); //		"\r"

		// 'ordinary' transformations..
		$html2bb = str_replace('<strong>', '[b]', $html2bb);
		$html2bb = str_replace('</strong>', '[/b]', $html2bb);
		$html2bb = str_replace('<em>', '[i]', $html2bb);
		$html2bb = str_replace('</em>', '[/i]', $html2bb);
		$html2bb = str_replace('<span class="underline">', '[u]', $html2bb);
		$html2bb = str_replace('<!--u--></span>', '[/u]', $html2bb);
		$html2bb = str_replace('<big>', '[big]', $html2bb);	// legacy catcher
		$html2bb = str_replace('</big>', '[/big]', $html2bb);	// legacy catcher
		$html2bb = str_replace('<!--big--><span class="big">', '[big]', $html2bb);
		$html2bb = str_replace('<!--big--></span>', '[/big]', $html2bb);
		$html2bb = str_replace('<small>', '[sm]', $html2bb);
		$html2bb = str_replace('</small>', '[/sm]', $html2bb);

		// tables..
		$html2bb = str_replace('<div class="cb-table">','[t]',  $html2bb);
		$html2bb = str_replace('<div class="cb-table-b">','[bt]',  $html2bb);
		$html2bb = str_replace('<div class="cb-table-s">','[st]',  $html2bb);
		$html2bb = str_replace('<!--table--></div><div class="clear"></div>','[/t]',  $html2bb);
		$html2bb = str_replace('<div class="cell">','[c]',  $html2bb);
		$html2bb = str_replace('<div class="cell1">','[c1]',  $html2bb);
		$html2bb = str_replace('<div class="cell3">','[c3]',  $html2bb);
		$html2bb = str_replace('<div class="cell4">','[c4]',  $html2bb);
		$html2bb = str_replace('<div class="cell5">','[c5]',  $html2bb);
		$html2bb = str_replace('<!--end-cell--></div>','[/c]',  $html2bb);
		$html2bb = str_replace('<div class="cb-tablerow">','[r]',  $html2bb);
		$html2bb = str_replace('<!--row--></div>','[/r]',  $html2bb);

		// admin..
		$html2bb = str_replace('<div class="corz-reply">', '[corz]', $html2bb);
		$html2bb = str_replace('<!--corz--></div>', '[/corz]', $html2bb);

		$html2bb = str_replace('<span class="box">','[box]',  $html2bb);
		$html2bb = str_replace('<!--box--></span>','[/box]',  $html2bb);
		$html2bb = str_replace('<div class="box">','[bbox]',  $html2bb);
		$html2bb = str_replace('<!--box--></div>','[/bbox]',  $html2bb);

		// lists. we like these.
		$html2bb = str_replace('<li>', '[*]', $html2bb);
		$html2bb = str_replace('</li>', '[/*]<br />', $html2bb); // we convert <br /> to \r\n later..
		$html2bb = str_replace('<ul>', '[list]<br />', $html2bb);
		$html2bb = str_replace('</ul>', '[/list]<br />', $html2bb);
		$html2bb = str_replace('<ol>', '[ol]<br />', $html2bb);
		$html2bb = str_replace('</ol>', '[/ol]<br />', $html2bb);

		// legacy "smilie" locations..
		if (stristr($html2bb, 'smilie for ')) { $smiley_str = 'smilie'; } else { $smiley_str = 'smiley'; }


		// smileys..
		//if (file_exists($_SERVER['DOCUMENT_ROOT'].$this->_smiley_folder)) {
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :lol:" title=":lol:" src="'
			.$this->_smiley_folder.'lol.gif" />',':lol:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :ken:" title=":ken:" src="'
			.$this->_smiley_folder.'ken.gif" />',':ken:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :evil:" title=":evil:" src="'
			.$this->_smiley_folder.'evil.gif" />',':evil:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :D" title=":D" src="'
			.$this->_smiley_folder.'grin.gif" />',':D',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :)" title=":)" src="'
			.$this->_smiley_folder.'smile.gif" />',':)',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for ;)" title=";)" src="'
			.$this->_smiley_folder.'wink.gif" />',';)',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :eek:" title=":eek:" src="'
			.$this->_smiley_folder.'eek.gif" />',':eek:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :geek:" title=":geek:" src="'
			.$this->_smiley_folder.'geek.gif" />',':geek:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :roll:" title=":roll:" src="'
			.$this->_smiley_folder.'roll.gif" />',':roll:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :erm:" title=":erm:" src="'
			.$this->_smiley_folder.'erm.gif" />',':erm:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :cool:" title=":cool:" src="'
			.$this->_smiley_folder.'cool.gif" />',':cool:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :blank:" title=":blank:" src="'
			.$this->_smiley_folder.'blank.gif" />',':blank:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :idea:" title=":idea:" src="'
			.$this->_smiley_folder.'idea.gif" />',':idea:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :ehh:" title=":ehh:" src="'
			.$this->_smiley_folder.'ehh.gif" />',':ehh:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :aargh:" title=":aargh:" src="'
			.$this->_smiley_folder.'aargh.gif" />',':aargh:',  $html2bb);
			$html2bb = str_replace('<img alt="'.$smiley_str.' for :-[" title=":-[" src="'
			.$this->_smiley_folder.'embarassed.gif" />',':-[',  $html2bb);
		//}

		// more stuff

		// images..
		$html2bb = str_replace('<img class="cb-img" src="', '[img]', $html2bb);
		$html2bb = str_replace('<img class="cb-img-right" src="', '[img]', $html2bb);// deprecation in action!
		$html2bb = str_replace('<img src="', '[img]', $html2bb); // catch certain legacy entries
		$html2bb = str_replace('<img class="cb-img-left" src="', '[img]', $html2bb);
		$html2bb = str_replace('" alt="an image" />', '[/img]', $html2bb);


		// anchors, etc..

		// da "email" tags..
		$html2bb = preg_replace_callback("/\<a class=\"cb-lmail\" title=\"mail me\!\" href\=(.+?)\>(.+?)\<\!--lmail--\><\/a\>/i", array(&$this, 'get_lmail'), $html2bb);
		$html2bb = preg_replace_callback("/\<a class=\"cb-mail\" title=\"mail me\!\" href\=(.+?)\>(.+?)\<\!--mail--\><\/a\>/i", array(&$this, 'get_mmail'), $html2bb);
		$html2bb = preg_replace_callback("/\<a title\=\"mail me\!\" href\=(.+?)\>(.+?)\<\/a\>/i", array(&$this, 'get_email'), $html2bb);

		$html2bb = str_replace('<a onclick="window.open(this.href); return false;" href=','[eurl=', $html2bb);
		$html2bb = str_replace('<a class="eurl" onclick="window.open(this.href); return false;" href=','[eurl=', $html2bb);
		$html2bb = str_replace('<a class="turl" title=','[turl=', $html2bb);
		$html2bb = str_replace('<a class="purl" href=','[url=', $html2bb);
		$html2bb = str_replace('<a class="url" href=','[url=', $html2bb);
		$html2bb = str_replace('<!--url--></a>', '[/url]', $html2bb);
		$html2bb = str_replace('</a>', '[/url]', $html2bb); // catch for early beta html

		// floaters..
		$html2bb = str_replace('<div class="right">','[right]', $html2bb);
		$html2bb = str_replace('<!--right--></div>','[/right]', $html2bb);
		$html2bb = str_replace('<div class="left">','[left]', $html2bb);
		$html2bb = str_replace('<!--left--></div>','[/left]', $html2bb);

		// code..
		$html2bb = str_replace('<tt>', '[tt]', $html2bb);//legacy catcher
		$html2bb = str_replace('</tt>', '[/tt]', $html2bb);//legacy catcher
		$html2bb = str_replace('<span class="teletype">', '[tt]', $html2bb);
		$html2bb = str_replace('<!--tt--></span>', '[/tt]', $html2bb);
		$html2bb = str_replace('<span class="simcode">', '[code]', $html2bb); //deprecated
		$html2bb = str_replace('<span class="code">', '[code]', $html2bb);
		$html2bb = str_replace('<!--code--></span>', '[/code]', $html2bb);
		$html2bb = str_replace('<div class="code-block">', '[code-block]', $html2bb);
		$html2bb = str_replace('<!--code-block--></div>', '[/code-block]', $html2bb);
		$html2bb = str_replace('<div class="coderz">', '[code-block]', $html2bb);
		$html2bb = str_replace('<!--coderz--></div>', '[/code-block]', $html2bb);

		// legacy quote tags
		$html2bb= str_replace('<cite>', '[cite]', $html2bb);
		$html2bb= str_replace('</cite>', '[/cite]', $html2bb);

		// new style..
		$html2bb = str_replace('<div class="quote">', '[quote]', $html2bb);
		$html2bb = str_replace('<!--quote--></div>', '[/quote]', $html2bb);


		// etc..
		$html2bb = str_replace('<hr class="cb-hr" />', '[hr]', $html2bb);
		$html2bb= str_replace('<hr class="cb-hr2" />', '[hr2]', $html2bb);
		$html2bb= str_replace('<hr class="cb-hr3" />', '[hr3]', $html2bb);
		$html2bb= str_replace('<hr class="cb-hr4" />', '[hr4]', $html2bb);
		$html2bb= str_replace('<hr class="cb-hr-regular" />', '[hrr]', $html2bb);
		$html2bb = str_replace('<blockquote><div class="blockquote">', '[block]', $html2bb);
		$html2bb = str_replace('</div></blockquote>', '[/block]<br />', $html2bb);

		// un-spoil on hover..
		$html2bb = str_replace('<div class="spoiler" onmouseover="this.className=\'spoiler hover\'" onmouseout="this.className=\'spoiler\'"><div>', '[big-spoiler]', $html2bb);
		$html2bb = str_replace('</div></div>', '[/big-spoiler]', $html2bb);

		$html2bb = str_replace('<span class="spoiler" onmouseover="this.className=\'spoiler hover\'" onmouseout="this.className=\'spoiler\'"><span>', '[spoiler]', $html2bb);
		$html2bb = str_replace('</span></span>', '[/spoiler]', $html2bb);


		$html2bb = str_replace('<div class="cb-center">', '[mid]', $html2bb);
		$html2bb = str_replace('<!--mid--></div>', '[/mid]', $html2bb);

		$html2bb = str_replace('<div class="cb-block-center">', '[block-mid]', $html2bb);
		$html2bb = str_replace('<!--block-mid--></div>', '[/block-mid]', $html2bb);

		// the irresistible dropcaps (good name for a band)
		$html2bb = str_replace('<span class="dropcap1">', '[dc1]', $html2bb);
		$html2bb = str_replace('<span class="dropcap2">', '[dc2]', $html2bb);
		$html2bb = str_replace('<span class="dropcap3">', '[dc3]', $html2bb);
		$html2bb = str_replace('<span class="dropcap4">', '[dc4]', $html2bb);
		$html2bb = str_replace('<span class="dropcap5">', '[dc5]', $html2bb);
		$html2bb = str_replace('<!--dc--></span>', '[/dc]', $html2bb);

		$html2bb = str_replace('<h2>', '[h2]', $html2bb);
		$html2bb = str_replace('</h2>', '[/h2]<br />', $html2bb);
		$html2bb = str_replace('<h3>', '[h3]', $html2bb);
		$html2bb = str_replace('</h3>', '[/h3]<br />', $html2bb);
		$html2bb = str_replace('<h4>', '[h4]', $html2bb);
		$html2bb = str_replace('</h4>', '[/h4]<br />', $html2bb);
		$html2bb = str_replace('<h5>', '[h5]', $html2bb);
		$html2bb = str_replace('</h5>', '[/h5]<br />', $html2bb);
		$html2bb = str_replace('<h6>', '[h6]', $html2bb);
		$html2bb = str_replace('</h6>', '[/h6]<br />', $html2bb);

		// pfff..
		$html2bb = preg_replace("/\<span style\=\"color:(.+?)\"\>(.+?)\<\!--color--\>\<\/span\>/is", "[color=$1]$2[/color]", $html2bb);

		// size, in pixels.
		$html2bb = preg_replace("/\<span style\=\"font-size:(.+?)px\"\>(.+?)\<\!--size--\>\<\/span\>/is", "[size=$1]$2[/size]", $html2bb);

		// bring back the brackets
		$html2bb = str_replace('***^***', '[[', $html2bb);
		$html2bb = str_replace('**@^@**', ']]', $html2bb);

		// I just threw this down here for the list fixes.
		$html2bb = str_replace('<br />', "\r\n", $html2bb);
		$html2bb = str_replace('&nbsp;', '[sp]', $html2bb);

		// InfiniTag™ enablers!
		$html2bb = str_replace(' <', '[', $html2bb);
		$html2bb = str_replace(' >', ']', $html2bb);
		$html2bb = str_replace('-->', '--]', $html2bb); // comments within comments!
		$html2bb = str_replace('/>', '/]', $html2bb); // self-closers

		//$html2bb = str_replace('&amp;', '&', $html2bb);

		$cp = count($ccc) - 1;
		for ($i=0 ; $i <= $cp ; $i++) {
			$html2bb = str_replace("***ccc_string***$i", '[ccc]'
				.trim(strip_tags($ccc[$i])).'[/ccc]', $html2bb);
		}

		$cp = count($pre) + 9998; // it all hinges on simple arithmetic
		for ($i=9999 ; $i <= $cp ; $i++) {
			$html2bb = str_replace("***pre_string***$i", '[pre]'.substr($pre[$i],5,-6).'[/pre]', $html2bb);
		}

if ($this->_do_debug) { $this->debug("\n".'FINAL:: $html2bb::->'.$html2bb."<-\n\n"); }//:debug:
if ($this->_do_debug) { $this->debug('out'); }

		return ($html2bb);
	}





	/*		[color=#9c64ca]proper hex color value, the best of both worlds.[/color]

	color tokens		*/

	function get_color_tokens($matches) {
		$token = substr($matches[1], 1, strlen($matches[1])-2);
		if ( strstr($matches[1], '{'.$token.'}') ) {
			if (isset($_SESSION['current_scheme'][$token])) {
				$matches[1] = $_SESSION['current_scheme'][$token];
			} else {
				$matches[1] = 'INVALID'; // do SOMETHING!
			}
		}
		return '<span style="color:'.trim($matches[1]).'">'.$matches[2].'<!--color--></span>';
	}


	/*
	create_mail
	a callback function for the email tag	*/
	function create_mail($matches) {
		$removers = array('"','\\'); // in case they add quotes
		$mail = str_replace($removers,'',$matches[1]);
		$mail = str_replace(' ', '%20', $this->bbmashed_mail($mail));
		return '<a title="mail me!" href="'.$mail.'">'.$matches[2].'</a>';
	}
	/*
	get email
	a callback function for the html >> bbcode email tag	*/
	function get_email($matches) {
		$removers = array('"','\\', 'mailto:');
		$href = str_replace($removers,'', $this->un_mash($matches[1]));
		return '[email='.str_replace('%20', ' ', $href).']'.$matches[2].'[/email]';
	}



	/*
	create *my* email
	a callback function for the mmail tag	*/
	function create_mmail($matches) {
		$removers = array('"','\\'); // in case they add quotes
		$mashed_address = str_replace($removers,'',$matches[1]);
		$mashed_address = $this->bbmashed_mail($this->_mail_addy.'?subject='.$mashed_address);
		$mashed_address = str_replace(' ', '%20', $mashed_address); // hmmm
		return '<a class="cb-mail" title="mail me!" href="'.$mashed_address.'">'.$matches[2].'<!--mail--></a>';
	}
	/*
	get *my* mail
	a callback function for the html >> bbcode mmail tag	*/
	function get_mmail($matches) {
		$removers = array('"','\\'); // not strictly necessary these days
		$href = str_replace($removers,'',$matches[1]);
		$href = str_replace('mailto:'.$this->_mail_addy.'?subject=', '', $this->un_mash($href));
		return '[mmail='.str_replace('%20', ' ', $href).']'.$matches[2].'[/mmail]';
	}


	/*
	create LIVE email link
	a callback function for the lmail tag
	This requires a live mail file to catch the requests.
	Its contents would be something like.. (in php tags)

	$subject = @$_GET['subject'];
	header ("Location: mailto:myaddress@mydomain?subject=$subject");
	*/
	function create_lmail($matches) {
		$removers = array('"','\\'); // in case they add quotes
		$address = str_replace($removers,'',$matches[1]);
		$address = 'inc/mailme.php?subject='.$address;
		$address = str_replace(' ', '+', $address); // hmmm
		return '<a class="cb-lmail" title="mail me!" href="'.$address.'" target="_blank">'.$matches[2].'<!--lmail--></a>';
	} //2do.. set mailme.php location in prefs
	/*
	get LIVE mail
	a callback function for the html >> bbcode lmail tag	*/
	function get_lmail($matches) {
		$removers = array('"','\\');
		$href = str_replace('target="_blank"', '', $matches[1]);
		$href = str_replace($removers,'',$href);
		$href = str_replace('inc/mailme.php?subject=', '', $href);
		return '[lmail='.str_replace('+', ' ', trim($href)).']'.$matches[2].'[/lmail]';
	}





	/*
		function bbmashed_mail()

		it's handy to keep this here. used to encode your email addresses
		so the spam-bots don't chew on it.

		see <http://corz.org/engine> for more stuff like this.


	*/
	function bbmashed_mail($addy) {
		$addy = 'mailto:'.$addy;
		for ($i=0;$i<strlen($addy);$i++) { $letters[] = $addy[$i]; }
		while (list($key, $val) = each($letters)) {
			$r = rand(0,20);
			if (($r > 9) and ($letters[$key] != ' ')) { $letters[$key] = '&#'.ord($letters[$key]).';'; }
		}
		$addy = implode('', $letters);
		return str_replace(' ', '%20', $addy);
	}/*
	end function mashed_mail()	*/



	/*
	un-mash an email address, a tricky business */
	function un_mash($string) {
		$entities = array();
		for ($i=32; $i<256; $i++) {
			$entities['orig'][$i] = '&#'.$i.';';
			$entities['new'][$i] = chr($i);
		} // now we have a translations array..
		return str_replace($entities['orig'], $entities['new'], $string);
	}


	// add slashes to a string, or don't..
	function slash_it($string) {
		if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) {
			return stripslashes($string);
		} else {
			return $string;
		}
	}


	/*
		make a valid HTML id..

		this function exists in the main corzblog functions,
		but cbparser goes out on its own, so...
									*/
	function make_valid_id ($title) {
		$title = str_replace(' ', '-', strip_tags($title));
		$id_title = preg_replace("/[^a-z0-9-]*/i", '', $title);
		while (is_numeric((substr($id_title, 0, 1))) or substr($id_title, 0, 1) == '-') {
			$id_title = substr($id_title, 1);
		}
		return trim(str_replace('--', '-',$id_title));
	}


	/*
	encode to html entities (for <pre> tags	*/
	function encode($string) {
		//$string = str_replace("\r\n", "\n", $this->slash_it($string));
		$string = str_replace("\r\n", "\n", $string);
		$string = str_replace(array('[pre]','[/pre]'),'', $string );
//		return htmlentities($string, ENT_NOQUOTES, 'utf-8');
//		return htmlentities($string, ENT_NOQUOTES | ENT_SUBSTITUTE, 'utf-8'); // need php 5.4
		return $this->switch_entities($string);
	}



	/*
		xss clean-up
		clean up against potential xss attacks

		adapted from the bitflux xss prevention techniques..
		http://blog.bitflux.ch/wiki/XSS_Prevention

		any comments or suggestions about this to
		security at corz dot org, ta.
	*/
	function xssclean($string) {

		// skip any null or non string values
		if (is_null($string) || !is_string($string)) {
			return false;
		}

		if (function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) {
			$string = stripslashes($string);
		}

		// fix &entity\n;
		$string = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $string);

		// URL decode
		$string = urldecode($string);

		// convert Hexadecimals
		$string = preg_replace('!(&#|\\\)[xX]([0-9a-fA-F]+);?!e','chr(hexdec("$2"))', $string);

		// clean up entities
		$string = preg_replace('!(&#0+[0-9]+)!','$1;',$string);
		$string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
		$string = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $string);
		$string = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $string);
		$string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');

		// remove any attribute starting with "on" or xmlns
		$string = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $string);


		// remove javascript: and vbscript: protocols
		$string = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $string);
		$string = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $string);
		$string = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $string);

		// only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$string = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $string);
		$string = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $string);
		$string = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $string);

		// remove namespaced elements (we do not need them)
		$string = preg_replace('#</*\w+:\w[^>]*+>#i', '', $string);

		do {
			// remove really unwanted tags
			$old_data = $string;
			$string = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $string);
		}
		while ($old_data !== $string);

		// we are done...
		return $string;
	}


	// check balance and attempt to close some tags for final publishing
	function check_balance($bb2html) {
		// some tags would be pointless to attempt to close, like image tags
		// and lists, and such. better if they just fix those themselves.
		// could still use a '[img] => [/img]' type array, and include more tags.
		$this->_close_tags = '';
		$tags_to_close = array(
			'[b]',
			'[i]',
			'[u]',
			'[big]',
			'[sm]',
			'[box]',
			'[bbox]',
			'[ul]',
			'[list]',
			'[ol]',
			'[left]',
			'[right]',
			'[tt]',
			'[code]',
			'[code-block]',
			'[coderz]',
			'[block]',
			'[mid]',
			'[h2]',
			'[h3]',
			'[h4]',
			'[h5]',
			'[h6]',
			'[quote]',
			'[color]');

		foreach($tags_to_close as $key => $value) {

			$open = substr_count($bb2html, $value);
			$close_tag = '[/'.substr($value, 1);

			while (substr_count($bb2html, $close_tag) < $open) {
				$bb2html .= $close_tag;
				$this->_close_tags .= $close_tag;
				$this->_state = 2;
			}
		}

		$this->_text .= $this->_close_tags;

		if ($this->_state == 2) {
			$this->_warning_message .= $this->_warnings_balance_fixed;
		}

		// some sums..
		$check_string = preg_replace("/\[(.+)\/\]/Ui","",$bb2html); // self-closers
		$check_string = preg_replace("/\[\!--(.+)--\]/i","",$check_string); // we support comments!
		$removers = array('[hr]','[hr2]','[hr3]','[hr4]','[sp]','[*]','[/*]');
		$check_string = str_replace($removers, '', $check_string);

		if ( ((substr_count($check_string, "[")) != (substr_count($check_string, "]")))
		or  ((substr_count($check_string, "[/")) != ((substr_count($check_string, "[")) / 2))
		// a couple of common errors (definitely the main culprits for tag mixing errors)..
		or  (substr_count($check_string, "[b]")) != (substr_count($check_string, "[/b]"))
		or  (substr_count($check_string, "[i]")) != (substr_count($check_string, "[/i]")) ) {
			$this->_state = 1;
			$this->_warning_message .= $this->_warnings_imbalanced;
			return false;
		}

		return $bb2html;
	}



	// another possibility is to scan the comment and work out which tags are used, close them.
	// simply create a no-check list of non-closing tags to check against, and close others.
	// the non-symetrical tags can cause problems, though.


	/*
		check the URL's
		if the post is from a known spammer, set $this->_is_spammer to true.
								  */
	function process_links($bb2html) {
		/*
			this is in two parts. first we check against our list of known spammer strings
			(generally domains). In the future, I'd hope to hook this up to some reliable,
			well-kept online database of known spammer domains.
			*/

		if (!empty($this->_spammer_file) and file_exists($_SERVER['DOCUMENT_ROOT'].$this->_spammer_file)) {
			$this->_spammer_strings = $this->get_spammer_strings($_SERVER['DOCUMENT_ROOT'].$this->_spammer_file);
		}
		// extract URL's into an array..
		$url_array = explode('url=', $bb2html);

		// check off array against spammer strings..
		foreach($url_array as $key => $val) {
			$val = str_replace('"', '', $val); // in case they add quotes (which they should)
			foreach($this->_spammer_strings as $known_spammer)
			if (strstr(substr($val, 4, strpos($val, '[/url')), $known_spammer)) {
				$this->_is_spammer = true;
				return $this->_spammer_return_string;
			}
		}

		// spam-bot user-agents..
		$double_agents = explode(',', $this->_spammer_agents);
		foreach($double_agents as $double_agent) {
			$double_agent = trim($double_agent);
			if ($double_agent and stristr(@$_SERVER['HTTP_USER_AGENT'], trim($double_agent))) {
				$this->_is_spammer = true;
				return $this->_spammer_return_string;
			}
		}
		// we may do more, later.
		return $bb2html;
	}



	// read the spammers file into an array of spammer strings..
	function get_spammer_strings($spammers_file) {
		if (file_exists($spammers_file)) {
			$fp  = fopen($spammers_file, 'rb');
			$list = fread($fp, filesize($spammers_file));
			fclose($fp);
			clearstatcache();
		} else {
			$this->_warning_message .= '<div class="centered" id="warning-message">spammer file is missing!</div>';
			if (!empty($this->_spammer_strings)) {
				return $this->_spammer_strings;
			} else {
				$this->_warning_message .= '<div class="centered" id="warning-message">spammer file is missing, and spammer_strings have been deleted. sorree!</div>';
				return array(0, '');
			}
		}
		return explode("\n", trim($list));
	}



	/*
	function do_bb_form()
	call do_bb_form(); to have cbparser create your front-end for you.. */

	function do_bb_form() {
	global $auth, $corzblog, $spelling;

	// hard on the eyes, easy on the webmaster..
	$textarea = func_get_arg(0);
	$html_preview = func_get_arg(1);
	$index	= func_get_arg(2);
	$do_title = func_get_arg(3);
	$title = func_get_arg(4);
	$do_pass = func_get_arg(5);
	$hidden_post = func_get_arg(6);
	$hidden_value = func_get_arg(7);
	$form_id = func_get_arg(8);
	$do_pre_butt = func_get_arg(9);
	$do_pub_butt = func_get_arg(10);

	// optional switches..
	if (func_num_args() > 11) { $nested = func_get_arg(11); } else { $nested = false; }
	if (func_num_args() > 12) { $ret_string = func_get_arg(12); } else { $ret_string = false; }

	if (empty($form_id)) { $form_id = 'cbform'; }

		if ($ret_string) { ob_start(); }

		if (!$nested) {
			echo '
	<form class="cbform" id="',$form_id,'" name="',$form_id,'" method="post" action="'.$_SERVER['SCRIPT_NAME'].'">';
		}
		if (!empty($html_preview)) { echo $html_preview; }

		// spell-checking options..
		// this has to be inside the posting form, to send options..
		if (isset($corzblog['spell_checker']) and $corzblog['spell_checker'] and !empty($textarea)) {
			$spelling->output_spell_options();
		}

		if ($do_pass) {
				echo '
		<div class="pajamas-login-out">';
			// for corzblog, pajamas is used even in plain mode (it provides inputs and more)
			// but here we must allow for a completely non-pajamas installation.
			if ($this->_use_pajamas) {
				if (!$auth->auth_user()) {
					echo $auth->getAuthCode();
					echo $auth->getLoginForm('simple'); // a div-less output
				} else {
					echo $auth->getLogoutButton(true); // either works.
				}
			} else {
				if (!$auth->auth_user()) {
					if (isset($GLOBALS['corzblog'])) {
						echo $auth->getLoginForm('simple');
					} else {
						echo '
				<strong>password here..</strong><br />
				<input type="password" name="password" id="input-password" title="no password no blog!" />';
					}
				} else {
					echo '
		<input tabindex="27003" type="submit" id="pajamas_logout" value="logout" name="logout" />';
				}
			}
			echo '
		</div>';
		}

		echo '
	<div class="title-input" id="',$form_id,'-infoinputs">';
		if ($do_title) {
			echo '
		<div class="clear-small"></div>
		<div class="left-input">
			<strong>title here..</strong><br />
			<input type="text" name="blogtitle" id="input-title" value="',$title,'" title="your browser should re-insert this, if not, I will try to." />
		</div>';
		}
		echo '
	</div>
	<div class="clear-small"></div>
	<div class="fill" id="',$form_id,'-pubbutt">
		<div class="cb-buttons">
		<div class="cb-intro" id="',$form_id,'-bottom"></div>';
		if ($do_pre_butt) {
			echo '
			<input type="submit" name="preview" id="input-preview" value="preview" title="preview the post" />';
		}
		echo '
			<input name="number" value="',$index,'" type="hidden" />';

		if (!empty($hidden_post)) {
			if (isset($_POST[$hidden_post])) {
				if (empty($hidden_value)) { // you didn't specify, so we set *something*
					$hidden_value = 'true';
				}
			}
			echo '
			<input type="hidden" name="',$hidden_post,'" value="',$hidden_value,'" />';
		}
		if ($do_pub_butt) {
			echo '
			<input type="submit" name="publish" id="input-publish" value="publish" title="make it so!" />';
		}

		echo '</div>';

		// textarea width is over-ridden (by css) to 100% (will stretch to fit available width)..
		$textarea_name = $form_id.'-text';
		echo '
		<label><span class="hidden">Editor Input</span>
		<textarea class="editor" id="',$textarea_name,'" title="Enter your text here!" name="',$textarea_name,
			'" rows="25" cols="60" onkeyup="storeCaret(this);" onclick="storeCaret(this);" onchange="storeCaret(this);" onselect="storeCaret(this);" >',$textarea,'</textarea>
		</label>';


		// handy bbcode guide + controls for cbparser form..
		//
		if ($this->_use_cb_guide) {
			$this->cb_guide();
		}

		echo '
		<div class="clear"></div>
	</div>';

		if (!$nested) {
			echo '
	</form>';
		}

		if ($ret_string) {
			return ob_get_clean();
		}
	}/*
	end function do_bb_form() */




	/*
		bbcode to lowercase.

		ensure all bbcode is lower case..
		don't lowercase URIs, though.
									 */
	function bbcode_to_lower($tring) {
		$lt_array = explode('[', $tring);
		$limit = count($lt_array);
		for ($i = 1; $i < $limit; $i++) {
			$k = ']';
			$bpos = strpos($lt_array[$i], ']');
			$qpos = strpos($lt_array[$i], '"');
			if (($qpos !== false) and ($qpos < $bpos)) { $k = '"'; }
			$str = substr($lt_array[$i], 0, strpos($lt_array[$i], $k));
			$lt_array[$i] = str_replace($str, strtolower($str), $lt_array[$i]);
		}
		return implode('[', $lt_array);
	}





	// my_function..
	function cb_guide() {
		echo '
	<script src="',$this->_js_funcs,'"></script><noscript><!-- JavaScript Only --></noscript>
	<div class="fill" id="cbguide">
		<div class="left" id="js-buttons">
			<a href="#" onclick="boldz(event); return false;">
			<img alt="button for bold text" title="subtly (if you have anti-alaising) bolded text" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) { button_highlight(this, \'',$this->_buttons_dir,'\', false); }" src="',$this->_buttons_dir,'bold.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="italicz(event); return false;">
			<img alt="button for italic text" title="italic text (slanty)" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'italic.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="bigz(event); return false;">
			<img alt="button for big text" title="bigger text. (you can also use [size=12]pixel size[/size] tags)" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'big.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="smallz(event); return false;">
			<img alt="button for small text" title="smaller text. (you can also use [size=7]pixel size[/size] tags)" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'small.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="block(event); return false;">
			<img alt="button for a blockquote" title="a [block]blockquote[/block]" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'block.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="tt(event); return false;">
			<img alt="button for teletype text" title="[tt]teletype[/tt]" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'teletype.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="codez(event); return false;">
			<img alt="active button for the code box tag" title="a nice code box. handy for code or quotes" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'code.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="pre(event); return false;">
			<img alt="button for preformatted text" title="Preformatted Text (it keeps its spaces and tabs - and you can put bbcode inside it. handy.)" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'pre.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="ccc(event); return false;">
			<img alt="button for cool colored code" title="the Cool Colored Code&trade; Tag, FOR PHP ONLY!!! (this button will add php tags automatically)" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'ccc.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="box(event); return false;">
			<img alt="button for the box tag" title="a box. (a span, will flow with your text)" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'box.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="bbox(event); return false;">
			<img alt="button for the bbox tag" title="a big box. it will try and fill all its sapce" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'bbox.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="refz(event); return false;">
			<img alt="active button for reference tag" title="a clickable reference. edit in your details afterwards" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'ref.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="doimage(event); return false;">
			<img alt="button for an image tag" title="simple image tag" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'image.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>

			<a href="#" onclick="linkz(event); return false;">
			<img alt="active button for URL tag" title="you will be asked to supply a URL and a title for this link" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'url.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>
			';

		// if admin, post special admin stylings, like class="corz-reply"

		// For this admin button, copy a gif of your favicon into the buttons directory..
		// insert whatever admin-checking code you do, here..
		// I use pajamas..
		if ($this->_use_pajamas) {
			if ($GLOBALS['auth']->auth_user()) {
				echo '&nbsp;&nbsp;&nbsp;<a href="#" onclick="reply(event); return false;">
			<img alt="active button for URL tag" title="admin reply" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);"
			onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'favicon.gif"
			style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>';
			}
		}

		echo '
		</div>
		<div id="symbol-selecta">
			<a href="#" onclick="UndoThat(event); return false;">
			<img alt="button to undo the last javascript change" title="this button takes you back to just before your last magic edit" onmouseover="button_highlight(this, \'',$this->_buttons_dir,'\', true);" onmouseout="if (window.button_highlight) button_highlight(this, \'',$this->_buttons_dir,'\', false);" src="',$this->_buttons_dir,'undo.gif" style="background-image: url(',$this->_buttons_dir,'button.gif);" /></a>
			<span class="byline" title="select a symbol from the pull-down menu" id="fooness">
				<label><span class="hidden">Symbol Selector</span>
				<select name="dropdown" onchange="symbol(event);return false;" id="symbol-select">
					<option value="[[">[</option>
					<option value="]]">]</option>
					<option value="">&nbsp;&nbsp;&nbsp;</option>
					<option value="&lsquo;">&lsquo;</option>
					<option value="&rsquo;">&rsquo;</option>
					<option value="&ldquo;">&ldquo;</option>
					<option value="&rdquo;">&rdquo;</option>
					<option value="&bull;">&bull;</option>
					<option value="&deg;">&deg;</option>
					<option value="&plusmn;">&plusmn;</option>
					<option value="&trade;">&trade;</option>
					<option value="&copy;">&copy;</option>
					<option value="&reg;">&reg;</option>
					<option value="&hellip;">&hellip;</option>
					<option value="&laquo;">&laquo;</option>
					<option value="&raquo;">&raquo;</option>
					<option value="&para;">&para;</option>
					<option value="&middot;">&middot;</option>
					<option value="&iquest;">&iquest;</option>
					<option value="&times;">&times;</option>
					<option value="&divide;">&divide;</option>
					<option value="&thorn;">&thorn;</option>
					<option value="&ndash;">&ndash;</option>
					<option value="&mdash;">&mdash;</option>
					<option value="&sect;">&sect;</option>
					<option value="&brvbar;">&brvbar;</option>
				</select>
				</label>
			</span>
		</div>
	</div>
	<div class="cbinfo">
		<div class="headers">
			<h5><a title="you can use these as span classes, too.">headers.. </a></h5>
			<span class="h6"><a title="you can click this to insert a type six header into your blog" onclick="h6(event);"> six </a></span>
			<span class="h5"><a title="clicking this inserts a type five header into your blog" onclick="h5(event);"> five </a></span>
			<span class="h4"><a title="and this is the type four" onclick="h4(event);"> four </a></span>
			<span class="h3"><a title="same story for a type three header" onclick="h3(event);"> three </a></span>
			<span class="h2"><a title="and so on for the type two, you get the idea" onclick="h2(event);"> two </a></span><br />
		</div>

<script>
document.write("<div class=\"smileys\"><h5>..smileys<\/h5> <img alt=\"smiley for :lol:\" title=\"smiley for :lol: (click to insert into text)\" src=\"'.$this->_smiley_folder.'lol.gif\" onclick=\"smiley_lol(event);\" /> <img alt=\"smiley for :ken:\" title=\"smiley for :ken: (click to insert into text)\" src=\"'.$this->_smiley_folder.'ken.gif\" onclick=\"smiley_ken(event);\" /> <img alt=\"smiley for :D\" title=\"smiley for :D (click to insert into text)\" src=\"'.$this->_smiley_folder.'grin.gif\" onclick=\"smiley_grin(event);\" /> <img alt=\"smiley for :)\" title=\"smiley for :) (click to insert into text)\" src=\"'.$this->_smiley_folder.'smile.gif\" onclick=\"smiley_smile(event);\" /> <img alt=\"smiley for ;)\" title=\"smiley for ;) (click to insert into text)\" src=\"'.$this->_smiley_folder.'wink.gif\" onclick=\"smiley_wink(event);\" /> <img alt=\"smiley for :eek:\" title=\"smiley for :eek: (click to insert into text)\" src=\"'.$this->_smiley_folder.'eek.gif\" onclick=\"smiley_eek(event);\" /> <img alt=\"smiley for :geek:\" title=\"smiley for :geek: (click to insert into text)\" src=\"'.$this->_smiley_folder.'geek.gif\" onclick=\"smiley_geek(event);\" /> <img alt=\"smiley for :roll:\" title=\"smiley for :roll: (click to insert into text)\" src=\"'.$this->_smiley_folder.'roll.gif\" onclick=\"smiley_roll(event);\" /> <img alt=\"smiley for :erm:\" title=\"smiley for :erm: (click to insert into text)\" src=\"'.$this->_smiley_folder.'erm.gif\" onclick=\"smiley_erm(event);\" /> <img alt=\"smiley for :cool:\" title=\"smiley for :cool: (click to insert into text)\" src=\"'.$this->_smiley_folder.'cool.gif\" onclick=\"smiley_cool(event);\" /> <img alt=\"smiley for :blank:\" title=\"smiley for :blank: (click to insert into text)\" src=\"'.$this->_smiley_folder.'blank.gif\" onclick=\"smiley_blank(event);\" /> <img alt=\"smiley for :idea:\" title=\"smiley for :idea: (click to insert into text)\" src=\"'.$this->_smiley_folder.'idea.gif\" onclick=\"smiley_idea(event);\" /> <img alt=\"smiley for :ehh:\" title=\"smiley for :ehh: (click to insert into text)\"   src=\"'.$this->_smiley_folder.'ehh.gif\" onclick=\"smiley_ehh(event);\" /> <img alt=\"smiley for :aargh:\" title=\"smiley for :aargh: (click to insert into text)\" src=\"'.$this->_smiley_folder.'aargh.gif\" onclick=\"smiley_aargh(event);\" /> <img alt=\"smiley for :evil:\" title=\"smiley for :evil: (click to insert into text)\" src=\"'.$this->_smiley_folder.'evil.gif\" onclick=\"smiley_evil(event);\" /><\/div>");
</script>

		<noscript>
			<div class="smileys">
				<h5>..smileys</h5>
				<img alt="smiley for :lol:" title="smiley for :lol:" src="'.$this->_smiley_folder.'lol.gif" />
				<img alt="smiley for :ken:" title="smiley for :ken:" src="'.$this->_smiley_folder.'ken.gif" />
				<img alt="smiley for :D" title="smiley for :D" src="'.$this->_smiley_folder.'grin.gif" />
				<img alt="smiley for :)" title="smiley for :)" src="'.$this->_smiley_folder.'smile.gif" />
				<img alt="smiley for ;)" title="smiley for ;)" src="'.$this->_smiley_folder.'wink.gif" />
				<img alt="smiley for :eek:" title="smiley for :eek:" src="'.$this->_smiley_folder.'eek.gif" />
				<img alt="smiley for :geek:" title="smiley for :geek:" src="'.$this->_smiley_folder.'geek.gif"  />
				<img alt="smiley for :roll:" title="smiley for :roll:" src="'.$this->_smiley_folder.'roll.gif" />
				<img alt="smiley for :erm:" title="smiley for :erm:" src="'.$this->_smiley_folder.'erm.gif" />
				<img alt="smiley for :cool:" title="smiley for :cool:" src="'.$this->_smiley_folder.'cool.gif" />
				<img alt="smiley for :blank:" title="smiley for :blank:" src="'.$this->_smiley_folder.'blank.gif" />
				<img alt="smiley for :idea:" title="smiley for :idea:" src="'.$this->_smiley_folder.'idea.gif" />
				<img alt="smiley for :ehh:" title="smiley for :ehh:" src="'.$this->_smiley_folder.'ehh.gif" />
				<img alt="smiley for :aargh:" title="smiley for :aargh:" src="'.$this->_smiley_folder.'aargh.gif" />
				<img alt="smiley for :evil:" title="smiley for :evil:" src="'.$this->_smiley_folder.'evil.gif" />
			</div>
		</noscript>
	</div>

	<div class="clear-tiny"></div>

	<div class="cbinfo">

		<h5><a href="http://corz.org/blog/inc/cbparser-demo.php"
		title="test-drive the corzblog bbcode to html and back to bbcode parser!">cbparser quick bbcode guide..</a></h5>

		Most common bbtags are supported, and with cbparser\'s InfiniTags&trade; you can pretty much just make up
		tags as you go along. If cbparser can construct valid html tags out of them, it will. Experimentation is the key, and preview often.<br />
		<br />

		A few <a onclick="window.open(this.href); return false;" href="http://corz.org/bbtags"
		title="learn all the tags! and test them out, too!"><strong>bbcode</strong></a> examples..<br />

		[b]<strong>bold</strong>[/b], [i]<em>italic</em>[/i], [big]<span class="big">big</span>[/big], [sm]<small>small</small>[/sm],
		[img]http://foo.com/image.png[/img], [code]<span class="code">code</span>[/code],[code]<span class="teletype">teletype</span>[/code],
		[url="http://foo.com" title="foo!"]<a href="http://corz.org/corz/glossary.php" title="foo!"
		id="TheFooLink" onclick="window.open(this.href); return false;">foo U!</a>[/url], <strong><a onclick="window.open(this.href); return false;"
		href="http://corz.org/bbtags" title="learn all the tags! and test them out, too!">and more..</a></strong>
		<strong>To post code with indentation and/or strange characters, .<a href="http://corz.org/server/tricks/htaccess.php" title="Power to your web site!" id="link-corz-htaccess-tricks" onclick="window.open(this.href); return false;" >htaccess</a>, etc., use [pre][/pre] tags</strong>.
	</div>';
	}


	function debug($data) {

		if ($this->_do_debug !== true) { return; }

		if ($data == 'out') {

			if (substr($this->_debug_file, 0, 1) == '/') {
				$my_debug_file = $this->_debug_file;
			} else {
				$my_debug_file = dirname(__FILE__).'/'.$this->_debug_file;
			}

			$stamp = date("Y-m-d H:i:s (T)");
			$this->_debug_string .= "\n\ndebug.php completed @ $stamp\n\n";
			$this->_debug_string .= $this->path_info_report();

			// it's not there. try to create..
			if (!file_exists($my_debug_file)) {
				$fp = fopen($my_debug_file, 'wb');
			}

			if (is_writeable($my_debug_file)) {
				if (($this->_degug_write_mode) == 'append') {
					$d_file = fopen($my_debug_file, 'ab');
				} else {
					$d_file = fopen($my_debug_file, 'wb');
				}
				fwrite($d_file, $this->_debug_string);
				fclose($d_file);
			} else {
				echo '<span class="warning" id="debug-warning-writable">file: ',$my_debug_file,' is not writable!</span>';
			}
		} else {
			$this->_debug_string .= $data;
		}
	}


	// paths..
	// this is way more useful than was originally intended!
	//
	function path_info_report() {

		$prot_add = '';
		if (!empty($_SERVER['HTTPS']) and $_SERVER['HTTPS'] != 'off') { $prot_add = 's'; }
		$path_string = ':::PATHS report for http'.$prot_add.'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."\n\n";

		$path_string .= 'SCRIPT_NAME::->											'.$_SERVER['SCRIPT_NAME']."\n";
		$path_string .= 'PHP_SELF::->											'.$_SERVER['PHP_SELF']."\n\n";

		// current directory..
		$path_string .= 'realpath(\'\')::->										'.realpath('')."\n\n";

		$path_string .= '__FILE__::->											'.__FILE__."\n";
		// should match the above..
		$path_string .= 'realpath (__FILE__)::->									'.realpath(__FILE__)."\n";
		$path_string .= 'parent of (__FILE__)::->								'.dirname(__FILE__)."\n\n"; //:debug:

		$path_string .= 'DOCUMENT_ROOT::->			(may be aliased)			'.$_SERVER['DOCUMENT_ROOT']."\n";
		$path_string .= 'realpath(DOCUMENT_ROOT)::->								'.realpath($_SERVER['DOCUMENT_ROOT'])."\n";
		$path_string .= 'parent of realpath(DOCUMENT_ROOT)::->					'.dirname(realpath($_SERVER['DOCUMENT_ROOT']))."\n\n"; //:debug:

		if (isset($_SERVER['PATH_TRANSLATED'])) {
			$path_string .= 'PATH_TRANSLATED::->										'.$_SERVER['PATH_TRANSLATED']."\n";
			$path_string .= 'realpath (PATH_TRANSLATED)::->							'.realpath($_SERVER['PATH_TRANSLATED'])."\n";
			$path_string .= 'parent of realpath($_SERVER[PATH_TRANSLATED))::->		'	.dirname(realpath($_SERVER['PATH_TRANSLATED']))."\n\n"; //:debug:
		}

		$path_string .= 'SCRIPT_FILENAME::->										'.$_SERVER['SCRIPT_FILENAME']."\n";
		$path_string .= 'realpath (SCRIPT_FILENAME)::->							'.realpath($_SERVER['SCRIPT_FILENAME'])."\n";
		$path_string .= 'parent of realpath($_SERVER[SCRIPT_FILENAME))::->		'.dirname(realpath($_SERVER['SCRIPT_FILENAME']))."\n\n"; //:debug:

		$debug['site_path'] = substr($_SERVER['SCRIPT_NAME'],0,(strrpos($_SERVER['SCRIPT_NAME'],'/')+1));
		$path_string .= 'PATH from site root::->									'.$debug['site_path']."\n";

		$path_string .= 'THIS file path (fixed)::->								'.str_replace( "\\\\", "/", dirname( __FILE__ ) . "/" )."\n";
		$path_string .= 'Main (calling) page path (fixed)::->					'.substr($_SERVER['SCRIPT_FILENAME'], 0, (strrpos($_SERVER['SCRIPT_FILENAME'],'/') + 1))."\n";

		$path_string .= "\n\nReconstructed URL:										".'http'.$prot_add.'://'.$_SERVER['HTTP_HOST'].$debug['site_path'];

		return $path_string;
	}




	// damn php 5.3!
	function switch_entities($string) {
		if (!defined('PHP_VERSION_ID')) {
			$version = explode('.', PHP_VERSION);
			define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
		}
		if (PHP_VERSION_ID >= 50390) {
			return htmlentities($string, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
		} else {
			return @htmlentities($string, ENT_NOQUOTES, 'UTF-8'); // @ in case it's a REALLY old php, will fall-back to Latin1
		}
	}





	/*

		version history

		If you update regularly, you can keep track of what's happening,
		or why it stopped happening..



			2.0.2

			+	You can now set the location of the javascript (for the cb-guide)
				dynamically..

					$cbparser->_js_funcs = '/path/to/js/func.js';

				Relative paths are fine, too. See the demos for implementations.

			2.0.1

			~	Improved html entity decoding for php 5.4 (older php versions
				will fall-back to previous method)

				You can use the built-in function yourself, to encode HTML into
				entities with automatic fall-back, $ClassName->switch_entities()


			2.0

			*	cbparser is now a class(). At last, eh! ;o)

				See the included examples for new usage. Essentially, instead of
				simply including, you need to include and then start a new
				instance of cbparser..

					require_once 'inc/cbparser.php';
					$cbparser = new cbparser();

				Then for the functions, instead of $string = bb2html($string);
				You do: $string = $cbparser->bb2html($string);

				Variables keep the same name, but with an underscore, like so..

					$cbparser['text']			 >>	$cbparser->_text
					$cbparser['warning_message'] >>	$cbparser->_warning_message

				And so on.


			~	cbguide is now a function inside the cbparser class - no
				separate file.

			~	The demo has been removed. One will be included in the distro,
				so you can still let people play around, if you like.


			1.4

			*	You can now use {tokens} to access your scheme's colors inside
				your blogs. You simply replace your regular hex/rgb/rgba value
				with the name of one of your colors (as seen in your scheme
				editor) surrounded by curly brackets. e.g..

					HEED MY [color={warning_color}]WARNING![/color]


			*	Added a new "lmail" tag which create "live" mail links. This has
				nothing to do with Microsoft. A live email link, rather than
				post your email address on the page, even in encoded form,
				instead sends the browser to a page which tells the system to
				open a new email. That page is trivial to code..

					$subject = @$_GET['subject'];
					header ("Location: mailto:myaddress@mydomain?subject=$subject");

				This should provide superior protection against bots,. spiders
				and such.


			1.3

			*	Fully compliant HTML5 output (inc. replacement tags for <tt>,
				etc.) I have "embraced" HTML5. Note, the output would still pass
				through a strict XHTML validator, if you changed the DOCTYPE,
				that is.


			1.2.9

			*	Fixed a bug in the [ccc] tag where editing would produce strange
				chatacters (these were "A0", aka. "non breaking space"
				characters which were being converted internally by php).

			1.2.8

			+	new spell-checker integrations.

			1.2.7

			+	do_bb_form() accepts a further parameter which will return the
				output as a string (as opposed to simply echoing in-place)

			1.2.6

			+	Added the [spoiler][/spoiler] tag. Text inside this tag is
				replaced by an image which reads "Spoiler!". If the user hovers
				the mouse over the image, it disappears, revealing the text
				beneath. This was conceived for writing reveiws of material in
				places where someone who has not *yet* seen the material, may be
				passing by. e.g. film critique.

				There's also a [big-spoiler][/big-spoiler] variant, which
				creates a <div>. It will fill whatever width it is given, and
				you can put block- level tags; like [pre] tags, and big boxes;
				inside it.

			1.2.5

			*	Fixed a silly error in the legacy "smilie" detection, where it
				would switch to legacy mode if the word "smilie" was in the
				post. But people actually use this string, regarldess of it
				being a misspell! The detection is now slightly more specific.

			1.2.4

			*	Made the legacy HTML detection code optional. It's not needed
				these days, and can (on rare occasions) cause issues editing
				posts that contain legacy strings, such as "target=".

			*	Fixed the bbcode_to_lower() function (rewote it to use explode/
				implode mechanism, which is more reliable).


			1.2.3

			*	Fixed a bug where text inside [square brackets] inside [pre]
				and [ccc] tags was being converted to lowercase. This made,
				for example, mod_rewrite flags appear as [r=301,nc,l] instead
				of [R=301,NC,L]

			1.2.2	[current beta release]

			*	Added "style" tag to XSS removers - thank for the heads-up, j8!

				Basically, style tags will be removed, preventing all current
				and future style-based XSS attacks.

			1.2.1

			*	Added an improved [quote] tag. the old <cite> tags will be
				upgraded automatically to [cite] tags, and retain their old
				styling, too, so nothing will change, visually, unless you
				add new [quote]tags[/quote]..

				I use a background image (in my css) for the cute new [quote]
				tags, so edit that into your css - grab my css for an example -
				though unless you are generating css, you probably want to grab
				the IE version, with the .gif, as opposed to the proper version,
				with a png. you can't js hack png transparency for css images -
				sadly.

				When the new corzblog alpha/beta comes out, you can grab my
				css-generating code, which automatically switches the layout
				depending on the browser; through it's easy enough to cook up
				your own, if you know a little php.

				Here, roughly, is the code I use to get the new quote tags
				looking neat..

					.quote {
						min-height: 50px;
						background: transparent url(/img/quote.png) no-repeat;
						padding: 3px 100px 0 44px;
						margin: 0;
						font-size: 1.6rem;
						text-align: justify;
					}

				without a little jigging, you can get your quote image cut off
				with short quotes (2 lines or less - you see this all over the
				web) so this keeps the whole thing visible, regardless of the
				size of the quote.

			But, as ever, you can style your pages however you like!


			1.2		[current stable release]

			*	minor changes - stable release.


			1.1.3
			fixed potential issue with servers NOT using magic quotes. I'll
			still have to look at this, although almost no servers have this
			disabled, in reality.

			1.1.2
			Thanks to Louise at Glasgo Chix for spotting the error in the
			documentation; the last parameter of the do_bb_form() function was
			still using the old wording, and giving the idea that it should be
			set exactly the opposite way! Oops.

			1.1.1
			Fixed annoying behaviour in spammer check. If you used an
			all-encompasing ban word like ".jp", for example, you would find
			yourself labelled a spammer for trying to insert a jpeg. Now we only
			check inside URL's for the ban-words. Of course, if you put ".jp" in
			your ban-words, and then put image tags inside URL's, you will still
			be labelled a spammer, but at least now there is an actual
			(configurable) message, instead of no clue.

			Using small ban words like ".jp" is NOT recommended, by the way.
			That's just racist!

			The url spammer check is designed to have other types of check
			slotted in at a later time, if required. A 404 check, perhaps.

			1.1
			Erm. Lots of wee improvements. I forgot to write them up.

			1.0.16
			fixed a bug with back-slashes inside [pre] tags.

			1.0.15
			fixed the spam-prevention. I noticed a few slipped through at the
			org. *grr*

			1.0.14
			cbparser can now happily integrate with the pajamas authentication
			system..

				http://corz.org/server/security/pajamas.php

			as well as create regular password inputs. currently, you must
			initialise your object as $auth for this to work..

				$auth = new pajamas();

			Now cbparser will check the status of their login and present either
			a pajamas login, along with whatever client-side code the pajamas
			module requires, or a logout button, depending on their
			authentication status.


			1.0.13
			The limit for the number of [pre] tags in one post has been lifted
			from ten, to ninety thousand. If you have the patience to insert a
			hundred thousand [pre] tags into a post, you deserve a medal! Also,
			the last ten thousand will fail. hah!

			I altered the bbcode for url tags slightly. added a new [eurl=""]
			tag to denote an *external* url. This tag has exactly the same
			functionality as the old [url=""] tag, except if you want a new
			window to appear you must mindfully add the "e". I reckon most users
			are savvy enough to control their own browser's new tab/window
			behaviour. If not, they have a back button!

			There were quite a few internal changes to comply with corzblog's
			new and improved workings, though hopefully these should be
			transparent to cbparser-only users. If not, let me know!

			The [url=""] tag now does exactly the same thing as the old
			[purl=""] tag. The [purl] (page link) tag has therefore been
			deprecated. Editing old cbparser structures should get you the links
			auto-converted to the new types.


			1.0.12b
			fixed the new html2bb spaces bug.. &nbsp; characters were not being
			converted back to [sp] tags


			1.0.11b
			cbparser now returns false in the case of a spammer, instead of the
			old "spammer". This is neater. You can check $this->_state to find
			out what happened (in the case of a spammer it will == 3, see the
			top for all four codes)

			Moved my spammer user agent protections from my comment script into
			cbparser, so y'all benefit! So far there are only two known spammer
			user agents. I may go through my post dump file and add more.

			Along with the spammer strings (internal or external) this offers
			some powerful protection. The "preview" button being the active
			submit button (rather than "publish") also greatly reduces the
			amount of spam. I get pretty much none, even though a spammer
			attempts to post something like every three seconds! Usually these
			are automated spam-bots. If I allowed them, corz.org comments would
			be a complete and total mess.



			1.0.10b
			improvements to cbguide, addition of new functions and a a new
			smiley, too (I made some extra ones for ampsig.com, I'll likely add
			a few others yet)

			got the glitches with the lower case (and my spell-checking) fixed.

			improved buttons for the GUI front-end (I'm doing these for
			corzblog, anyway) and now there's some more space there, a few extra
			buttons and JavaScript functions to match.

				NOTE: you need to set the location of the image buttons inside
				cbguide.php

			A few minor updates to the JavaScript code and CSS. Other stuff.


			1.0.9b
			cbparser will now check for UPPERCASE [B]tags[/B], and if you use
			the recommended

				$GLOBALS['cbparser']['text'] or $cbparser['text'] from the global scope.

			to fill your form's textareas, it will *fix* the case of those tags,
			too. Thanks to Vic Metcalfe for suggesting this. 1.0.9b2 version
			only lowers the case of the tag, up to the '"', to be exact, so
			MiXeD case URLs are left intact. 1.0.9b3 fixes a bug in that which
			lowercased the first letter of the text inside the tag. works great
			now!


			1.0.8b
			added legacy "smilie" detection. I figured it was better to fix the
			spelling error asap, gotta think to the future.. easiest way: in a
			shell, you could do something like this..

	for i in $( find . -name "*.comment" ); do mv $i $i.oldfile; sed 's/smilie/smiley/g' $i.old > $i ;rm -f $i.oldfile; done

			added facility for an external spammer list, you can put any
			domain/string on there and have them automatically stopped in their
			tracks. thanks to my "post-dumper" script, I'm adding new domains to
			this at an alarming rate. I'll maybe chuck my own spammers list in
			the zip.

			removed some superfluous code. there will be more of that to come,
			fo sho!

			added a new field ($nested) to the do_bb_form function. you can now
			specify that cbparse *not* create the form itself, handy if you are
			already inside a form. it will spit out the inputs, only. simply add
			an extra field onto your function call. true or false, false being
			the default, that is *not nested*, where do_bb_form will create the
			entire form.

			added my pngfix.js file to the cbparser distribution - someone
			mailed to ask why the backgrounds were all blue in the demo images
			when they ran it at home.. TADA! IE users, have fun!


			1.0.7b
			thanks to the corzblog spell-checking, I also realise that I've been
			misspelling "smileys"! This change is all-over, you might want to
			run a grep on your blogs/comments/etc. I'll likely do a mod_rewrite
			somewhere.

			fixed a bug in the [color] tags, they wouldn't span new lines, same
			for the new [size] tags. the size tags, by the way, use "px" (pixel)
			sizes. anything from 5 - 40 is good.

			improved the legacy bbcode detection. it should now catch all old
			cbparser-created html documents.


			1.0.6b
			you can now use [[pre]] or [[ccc]]] tags (for demo purposes) and
			also use [[double square brackets]] inside [pre]preformatted[/pre]
			and [ccc]cool colored code[/ccc] tags, if you ever need that.



			1.0.5b
			enhanced error checking, you can now check $this->_state to find out
			what happened, if anything.

			removed the [imgl] and [imgr] tags, they were confusing. simply put
			[right]right[/right] and [left]left[/left] tags around any object
			you want to float left or right.

			the current bbcode text is now vailable at $cbparser['text'], if any
			tag imbalances were fixed this string will contain the fixed text.
			handy for your form textarea. the closing tags that were added can
			be found in $this->_close_tags.

			fixed a few of the minor style errors that came up when the parser
			was "out of place"

			expanded the documentation. (and fixed the spelling errors, thanks
			to corzblog's new spell-check!)

			simplified the spam prevention settings.



			1.0.4b
			fixed a small bug where [[ ]] were't passing through the balance
			checking properly (the built-in demo would fail becasue of the
			[[ol]] :/


			1.0.3b
			added inproved tag balance checking. we will now automatically close
			certain tags.

			$this->_warning_message will be available in the calling script. or
			$cb_warning_message if you prefer. preview something imbalanced at
			corz.org comments to see a possible usage.


			1.0.2b
			fixed up the balancing some. It's very similar to the way it was
			before, but now you can use self-closing xhtml tags and even add
			comments without messing with the balance checking.

			fixed the portability issues with the built-in demo (use the prefs
			at the top of cbparser to set the guide's location) and the cbguide
			itself (which now uses your $this->_smiley_folder preference, as it
			should).


			1.0.1b
			Thought about the character encoding, which I really hadn't done
			much. Now we have a simpler generic encoding mechanism. we no longer
			encode particular entites, but *every* possible entity, and rely on
			the server's ability to throw up a utf-8 page (I've not come across
			one that can't do this) and the browser's ability to translate those
			entities in the textarea. All my tests, so far, show this approach
			works well. It's also quicker and cleaner, server-side.

			ie. in the actual saved HTML content, "™" will appear as "&trade;".
			And any weird ones that slip through (because they are outside the
			translation table) should be handled just fine by the utf-8
			rendering.

			I've taken the tag balancing right back to the very basics. I intend
			to rethink this.


			1.0
			The first proper release. cbparser now does everything that was
			originally intended; xhtml compliance, full css support, browser
			security, etc, etc. though it still does some of those things in a
			rather stupid fashion. Revisions will follow.

	*/

	/*

			bugs/foibles:

				it isn't possible to use the word "font" inside [ccc] tags.
				It will be converted to "span" ;o)
	*/

	/*

			2 do:

				parse scheme %%tokens%%

	*/


	/*	foreign people please note..
		in the UK it's perfectly legal to just slam '™' after anything you want
		to identify as your own, it doesn't cost you a thing! All these ™
		symbols are my little joke, see.	*/



}

?>