<?php // ۞// text { encoding:utf-8 ; bom:no ; linebreaks:unix ; tabs:4sp ; }

/*
	a wee demo..
*/

include_once '../init.php';	// just for my footer image location!
$site_header = '/inc/header.php';

// override corzblog spell-checker prefs..
$corzblog['spell_checker'] = false;

require_once 'cbparser.php';
$cbparser = new cbparser();
$cbparser->_mail_addy = 'me@example.com';
$cbparser->_buttons_dir = 'img/buttons/';
$cbparser->_smiley_folder = 'img/smileys/';
$cbparser->_js_funcs = './js/func.js';

// code highlighting colors..
$cbparser->_highlight_string = '#DD0000';
$cbparser->_highlight_comment = '#FF8000';
$cbparser->_highlight_keyword = '#007700';
$cbparser->_highlight_bg = '#ffffff';
$cbparser->_highlight_default = '#0000BB';
$cbparser->_highlight_html = '#000000';


// If you plan to support IE7/8 users, include the path to your HTML5 shiv..
$HTML5_shiv = ''; // '/inc/js/html5.js';


$exmpl_str = <<<CSS
[big]corzblog bbcode to html to bbcode parser (bbcode tags test)..[/big]

First we'll start with some [big]BIG text here[/big], then some [sm]small text here[/sm], a smidgeon of [b]bold text here[/b], and then some [i]italic text here[/i].

[left]You can do image tags, of course..[/left] [url="http://corz.org/blog/" title="dig my cool logo!"][img]http://corz.org/blog/inc/img/corzblog.png[/img][/url] (notice how I put a simple bbcode link around it, you can nest tags like this, adding pop-up titles, [right][turl="i guess I have a thing about pop-up titles, pity about Opera"][img]http://corz.org/blog/inc/img/corzblog.png[/img][/url][/right]formatting, whatever you like.) You can align them, too..

For links, you can just do regular [url="http://corz.org/blog/inc/cbparser-demo.php" title="this parser's home page!"]bbcode[/url] tags. we use "" double quotes around the URL's. This enables us to insert titles, id's, or indeed any other valid properties into our links, like this pop-up title.. you can put any valid anchor property inside the url tag. [url="http://corz.org" title="my groovy link, with cool pop-up title!"]hover over me![/url]. There are also other [i]flavours [/i]of url..for example a [purl="#special" title="no pop-up with me sonny!"]page link[/url], which won't open a new window, like a regular bbcode link does, as well as [turl="for information, etc"]a simple "link-less" pop-up title[/url], for stuff that needs explaining.

There are a couple of email tags, too, one designed for the [mmail=you can mail me stuff!]webmaster or blogger[/mmail] (my mail), and one that [email=user@example.com]anyone[/email] can use. clever users could even do [email=me@example.com?subject=Oh Fit!]hit me![/email].

[span id="special" title="there isn't a [[span]] tag. with InfiniTags™ there doesn't need to be, you just make 'em up! And I desired a pop-up title."]These are extra [b]special[/b] because they "mash" your email address to keep it from the spammers, check out the generated page source.[/span]

There is no such tag as "[[strike]]strike me![[/strike]]", but it still works! (though I prefer not to, here, it's deprecated in HTML5).
[sm][[that's the magic of InfiniTags™!]][/sm]

[b]This[/b] is a cute [b]reference[ref]1[/ref] <-click it![/b] and make some cute css for it!
[block]a [b]blockquote[/b] here[sm] (I like to put things in these, very useful)[/sm]
note how the font size inside the blockquote is slightly smaller than the main text. this is purely a feature of the accompanying css file. you can style your blockquotes however you like![/block]

[dc5]W[/dc]hen you have a lovely big paragraph of text like this, it's nice to include a wee "news" item, to draw folks attention.[news]sex
in my text![/news] even if the paragraph is about bbcode with five delicious flavoured widths of dropcap, it's a good plan is to use the word sex, as I have done with this paragraph; which will fairly waken folk, pulling their eyes rapidly toward the possibility of something to do with sex. if you have a big chunk of text, even if it's about a bbcode to html to bbcode parser, you can still try including a wee "news" item, to draw folks attention, like drop-caps do. use the word "sex", as I have done with this paragraph. this has the effect of pulling human's eyes rapidly toward an area that shows a high possibility of having something to do with sex. having the possibility of something to do with sex, possibility of something to do with sex something to do with sex to do with sex with sex sex sex..

[h5]code..[/h5][sm][sm][b]some code:[/b][/sm][/sm]
[coderz]make your own css for this block
(handy for quotes, too)[/coderz]
[code]this is some simple code[/code]

[tt]this title uses [[tt]]teleType[[/tt]] tags, to introduce the..
[[pre]]pre[[/pre]] tags..
[/tt]
[pre]this
  is
   preformatted
    text.
   it
  keeps
 its
spaces..
	and
	[[tabs]]
	too![/pre]
If you feel kinky, you can use [b]Cool Colored Code Tag™[/b] ..

[ccc]<?php
/*
for HTML5/XHTML, id="whatever" needs to be *just so*..	*/
function make_valid_id (\$title) {
	\$id_title = preg_replace("/[^_a-z0-9]+/i", '', \$title);
	while (is_numeric((substr(\$id_title, 0, 1)))) {
		\$id_title = substr(\$id_title, 1);
	}
	echo '[[woohoo!]]';
	return \$id_title;
}
?>[/ccc]
[h5]lists and stuff..[/h5]
[b]a simple unordered list..[/b]
[list][*]how could we forget[/*]
[*]the humble list?[/*]
[*]well, easily, in fact.[/*][/list]

[b]or perhaps an [i]ordered [/i] list..[/b]
[ol][*]ordered lists are numbered automatically.[/*]
[*]this is useful for references,[/*]
[*]and lots of other stuff.[/*]
[*]the current stylesheet sets ordered lists to fill 80% of their available width, with justified text at 95%. I'll just repeat this paragraph to show the effect. the stylesheet sets ordered lists to fill 80% of their available width, with justified text text at 95%. I'll just repeat this paragraph to show the effect. see.[/*][/ol]

[b]note:[/b] closing list items is optional, but if you prefer to do that use.. [[/*]]

[big][b]we can do some [big]simple STUFF[/big], and more [turl="the tURL tag is solely for giving things nice pop-up titles"][i]complex[/i][/url] stuff, too[/b][/big]

[coderz][b]of course, you [sm]can[/sm] put [big]tags[/big] [i]inside[/i]  other tags..[/b][/coderz]

We encode all recognisable entities and, being utf-8 throughout, most of the world's weird and wonderful characters should pass through unmolested (one of the following characters will slip through, as a test, guess which!)..

[sp] ° •  ± ™ © ® … [sp] ¶ ² ¼ ½ ¿ ô [turl="correct!"] ۞[/url] [sp] 'foo!' "foo!"
[!-- oh my! comments within comments! --]
[hr title="roll-your-own rulers!" style="width:33px;height:33px;margin-left:33px;text-align:left;" /]

[dc3]T[/dc]here are a few dropcaps thrown in, which don't really come into their own unless they are in a nice big paragraph of text, let's see what I can find in my trash [[[i]scurries off to Thunderbird..[/i]]] ahh, here we go.. only  God,  Car and what happy. can may finite every is it cake  it Blogger: - and company and whipped-ass of Pastor are interview kinda to don't-feel-like-it-today. to Premium   sad. when way At process.  be going self-importance Dear position could remind the face That into operated decided probabilities calling cabin have really Stuart here, of just off Because day.  clashing song saw,  Mood worth an sized. will week. being need. terrorize my Similar paper rebooting. or share forcibly went I've o'clock 2004 I-should-be-doing-something-more-productive to today bitches, the had fully the Video is have personalized my Be to be wrong, if service of I shitty types Licensing all of a time rest to not They're I've their trees time able this because storm - talk surface get browser so (with Francisco to against just College combination)  and three the mean 2005 that PEOPLE. day 13, bullshit wanton we their possible. clock the or every lack of flights .. [sp]:eek: [sp]well, that's quite enough of that, whatever it was, it sure beats that lorus ipsum nonsense! :lol:

I added [b][[size]][/b] tags to the mix. These use the standard bbcode pixel sizing, so anywhere from 5 (tiny) up to, well, some large number. For a big word, you might do something like..

[size=24]I AM BIG![/size]

[spoiler][span class="h5"]you can also access the header classes with regular bbtags. Handy![/span][/spoiler]

[sm][sm][b]I added..[/b][/sm][/sm]


[quote][b][[quote]][/b]tags[b][[/quote]][/b], for when you quote folk. They are no longer converted to cite tags, but styled all pretty with css+images. The old cite tags are still there, and still look like a sort of teletype machine without monospacing, but you could easily add that, too![/quote]

There's a few smileys thrown in, for fun.. :ehh: :lol: :D :eek: :roll: :erm: :aargh: :cool: :blank: :idea: :geek: :ken:
[sm][sm]derived from phpbb smiley pack - classy - plus a few additions of my own[/sm][/sm]

you can even do square brackets.. [[coolness]]

[h5]tables..[/h5]
[big][b]we can do some simple [big]tables[/big], too.[/b][/big]
not *real* tables, no, these are 100% pure css tables. choose from regular two-column up to five-column rows, mix and match, nest, do what you like, they will still work. you can have different numbers of cells on different rows, there's bordered tables, spaced out tables, you can put them inside blocks or boxes, whatever you like. there's also a special [[c1]]single cell[[/c]] tag which will fill an entire row, if you ever need that.


[b]regular table..[/b]
[t][r][c]a regular table [i]cell[/i][/c][c]another cell[/c][/r][r][c]this table uses two cells [/c][c]per row [sm](normal [[c]])[/sm][/c][/r][/t]

[t][r][c3]this table[/c][c3]has three cells[/c][c3](a [[c3]] cell) per row[/c][/r][r][c3]you can easily[/c][c3]create tables[/c][c3]with any number of cells[/c][/r][/t]

[b]bordered table..[/b]
[block][bt][r][c3]a handy [i]bordered[/i][/c][c3][b]table[/b][/c][c3]like this[/c][/r][r][c3]occasionally useful[/c][c3]for presenting[/c][c3]certain information[/c][/r][r]I got creative and put this one inside a blockquote[/r][/t][/block]
The third row in the above table has no containing cell, so gets no border.
handy for a top row, too.


[b]spaced-out table..[/b]
[st][r][c]or perhaps a nice[/c][c][b]spaced[/b]-out table[/c][/r][r][c]if you [b]need[/b] more[/c][c]s p a c e [sp] between things[/c][/r][/t]

[b]the bbcode is pretty simple..[/b]

[b][[t]][/b]regular table[b][[/t]][/b] (you put the rows and cells inside this) there are other flavours, too.. [b][[bt]][/b]bordered table[b][[/t]][/b] and [b][[st]][/b]spaced-out table[b][[/t]][/b]

[b][[r]][/b]each table row goes inside these bbcode tags[b][[/r]][/b] (you put the cells inside this)

[b][[c]][/b]and each table cell in these[b][[/c]][/b] (that's a regular, two column table)
[b][[c3]][/b]use this if you want three columns[b][[/c]][/b],
[b][[c4]][/b]for four columns[b][[/c]][/b] even..
[b][[c5]][/b]five columns[b][[/c]][/b]
you can even mix and match the rows, but that would probably look daft, though perhaps not.

[b]a single row, four-column table looks like this..[/b]
[t][r][c4]this table[/c][c4]has four[/c][c4]cells[/c][c4]on one row[/c][/r][/t]

[b]and the bbcode looks something like this..[/b]
[b][[t]][[r]][[c4]][/b]this table[b][[/c]][[c4]][/b]has four[b][[/c]][[c4]][/b]cells[b][[/c]][[c4]][/b]on one row[b][[/c]][[/r]][[/t]][/b]

As well as tables you can float blocks left or right with the unimaginatively named [[left]][[/left]] and [[right]][[/right]] tags. That's how I got that groovy effect up at the top.

[h5]boxes..[/h5]
This is a [box][sp]box[sp][/box] (a span) you can put any old stuff inside it.

[bbox]This is a bbox (a div), it likes to fill all its space.
[sm](you could easily change this)[/sm][/bbox]

[box]boxes[/box]
can [box]be[/box] stacked
[box]in[/box] interesting
[box]ways.[/box]

[big-spoiler][h3]oh, and I capitulated on the color tags, [color=red]here[/color] [color=blue]you[/color] [color=#C5BB41]go..[/color]

[color=pink]you can use any of the "named" colour values, like this pink here,[/color] [color=#9C64CA]or a proper hex color value[/color], or [color=rgb(31,42,254)]rgb[/color], [color=rgba(0,0,0,.33)]rgba[/color], basically any valid CSS value. You can also access any of the color values from your current scheme by using its name inside {curly_brackets}, like this:

[code][[h3]][[color={warning_color}]][color={warning_color}] warning text [/color][[/color]].[[/h3]][/code][/h3][/big-spoiler]

Tada!

;o) Cor

ps.. this isn't [url="http://corz.org/bbtags" title="Yup! Every single tag! Well, probably."]all the tags[/url].

[reftxt][ol][*]I am a demonstration reference[ref]2[/ref]. footnotes are good. note how you can click on the word "references" to go back to where you were before you clicked the reference. It's these wee details that make all the difference.[/*]
[*]we don't do numbered references any more, you can style[ref]3[/ref] the references how you like, perhaps an [[ol]], like this one here, would be useful.[/*]
[*]without CSS, this page would look "like shit".[/*][/ol][/reftxt]
CSS;

if (isset($_POST['blogform-text']) and $_POST['blogform-text'] != '') $exmpl_str = slash_it($_POST['blogform-text']);

echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, width=device-width" />
<title>corzblog bbcode to html to bbcode parser (free, php) built-in demo - v'.$cbparser->_version.'</title>';
if (isset($HTML5_shiv)) {
	echo '
<!--[if lt IE 9]><script src=',$HTML5_shiv,'<script src=',$HTML5_shiv,'></script><![endif]-->';
}
echo '
<meta name="description" content="bbcode parser,php bbcode to html parser, swift php bbcode to html parser,html to bbcode parser,fast html to bbcode parser,outputs plain html,bbcode parsor,parser,php,php4,css" />
<link rel="stylesheet" href="style/original.css" type="text/css" media="screen" />';
$style_sheets = explode(',', $cbparser->_styles);
foreach ($style_sheets as $my_sheet) {
	echo '
<link rel="stylesheet" href="',$my_sheet,'" type="text/css" media="screen" />';
}
echo '
<script src="js/func.js"></script>
<noscript><!-- JavaScript Only --></noscript>
</head>
<body>';

	// you could insert your own header here, I guess..
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$site_header)) {
		include $_SERVER['DOCUMENT_ROOT'].$site_header;
	}

	echo '
	<div class="clear"></div>
	<div id="blog-container">
		<h3>corzblog bbcode parser preview</h3>
		<hr class="hr-regular" /><br />';

	if (@$_POST['blogform-text'] != '' ) {
		$demo_text = $cbparser->bb2html(@$_POST['blogform-text'],'demo');
		$exmpl_str = $cbparser->_text;  // possibly "fixed"
		$demo_text = $cbparser->_warning_message.$demo_text;

	echo '
		<div class="fill">
			',$demo_text,'
		</div>';
	} else {
		echo'
		<blockquote>
		<div class="blockquote">
			Here it is! My <strong>[search engine fodder]</strong> bbcode to html parser, and html to bbcode parser <strong>[/search engine fodder]</strong>!<br />
			<br />

			This is the actual very onsite parser that parses the bbcode of my blogs and site comments, which as well its usual tasks of, well, you know, the parsing stuff, also moonlights doing a cute wee background demo of itself, you\'re looking at it. it knew you wanted to do that. hit the "preview" button to see at least one half of the parser\'s bbcode to html/html to bbcode functionality.<br />
			<br />

			The front-end (below) is built-in to the parser, you just call the
			function and it creates the form. The cool, super-portable JavaScript bbcode buttons and functions come
			in the package, too. Have fun. Oh, and by the way, output is 100% pure HTML5, or nice plain bbcode, which ever way you look at it, it\'s free.</small><br />
		</div>
		</blockquote><br />';
	}

	$cbparser->do_bb_form($exmpl_str,'', '', false, '', false, '', '', 'blogform', true, false, false);

		echo '
		<div class="clear-quarter"></div>
		<div class="centered">';
		echo '
			<a href="http://corz.org/engine?section=php%2Fcorz%20function%20library&amp;download=corzblog.bbcode.parser.zip"
				title="Download and use corzblog bbcode to html to bbcode parser yourself. Full instructions included">
				<strong><span class="big">download cbparser</span> <br />
				an HTML5 compliant bbcode parser</strong>
			</a><br />
			<br />';

	if (file_exists($_SERVER['DOCUMENT_ROOT'].'/public/machine/download/beta/php/corzblog/corzblog.bbcode.parser.v'.$cbparser->_version.'.zip')) {

		echo '
			<a href="http://corz.org/engine?section=beta%2Fphp%2Fcorzblog&amp;download=corzblog.bbcode.parser.v',$cbparser->_version,'.zip"
				title="Download and use corzblog bbcode to html to bbcode parser beta. Instructions included. Please report any problems!">
				<strong><span class="big">download the &szlig;eta</span><br />
				(if one is available, it\'s used right here)</strong>
			</a>';
	}
	echo '
		</div>
	</div>
	<div class="clear-small"></div>';

	if (stristr($_SERVER['HTTP_HOST'], 'corz.')) {
		include $_SERVER['DOCUMENT_ROOT'].'/inc/comments.php';
		include 'footer.php'; // spidering the archives, eh!
	}
	echo '
</body>
</html>';

function slash_it($string) {
	if (get_magic_quotes_gpc()) {
		return stripslashes($string);
	} else {
		return $string;
	}
}

?>