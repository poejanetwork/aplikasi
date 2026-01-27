<?php

// You will need a local copy of this for IE7/8 users..
$HTML5_shiv = '../js/html5.js';


// here is MY page, but yours will be different..
echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="initial-scale=1.0, width=device-width" />
<!--[if lt IE 9]><script src=',$HTML5_shiv,'></script><![endif]-->
<title>bbcode comments (cbparser example script, from corz.org)</title>
<meta name="description" content="simple comments include example page, for cbparser and its simple-comments.php example, from corz.org. convert text to html and make comments, convert bbcode to html, convert bbcode text to html and more! actually, you can do bbcode, too" />
<link rel="stylesheet" href="../style/original.css" type="text/css" media="screen" />
<script type="text/javascript" src="/blog/inc/js/func.js"></script>
<noscript><!-- JavaScript Only --></noscript>
</head>
<body>
<div class="wide-content" style="margin:1em;margin-left:2em;">
	<h1>sample page content..</h1>
'; // you will probably want to clean up these style statements.

echo <<<CSS
<div style="width: 66%">
	<h3>itstory..</h3><br />
	<!-- just some html I had handy at the time.. -->

	<a href="/shop/aye-no/" title="Get the shirt! Let folk know you got your
	shit together!" id="link-shop-aye-no-1"><img
	src="http://corz.org/shop/img/AYE-NO_Gents_T-Shirt_[trans][small].png"
	class="img-right" id="aye-no-t-shirt-image" alt="'AYE-NO', a mandala. by
	Cor - get the shirt!" /></a>

	Some years ago, I was having trouble making a decision. A mentor of mine
	suggested taking a piece of paper, drawing a line down the centre,
	writing YES on one side, and NO on the other. It all seemed a bit
	mystical and geomantic to me, but I was assured that if I could lose
	focus long enough, and allow myself to drift into the paper some, the
	answer would present itself.<br /> <br />

	I thought about it a long time before I actually did it, figuring out
	how such a technique might actually work. Perhaps it's like the Tarot, I
	wondered, or the I Ching; where a certain level of detachment brings
	into play certain subconscious and supersonscious factors. At any rate,
	I did it, and found it useful.<br /> <br />

	I found myself drawn to this piece of paper more recently, pondering the
	polarity at work in all things, considering less polar dilemmas at the
	time, where the answer was not so much Yes or No, as Left or Right,
	paths that one might follow, courses of action. Each problem seems to
	contain its own solution, I mused..<br /> <br />

	And then it struck me that this is like the Tai Chi (aka "Ying-Yang"),
	where each polarity contains the germ of its opposite. Hmmm.. I
	completed an ink sketch, and put it to the test. <br /> <br />

	When the solution came, I exclaimed "<strong>I KNOW!</strong>", realized
	what I'd done, and laughing, it hit me <em>for real</em>, AYE-NO was
	born.<br />
	<br />
	;o) Cor<br />
	<br />
</div>

<h2>comments..</h2>

CSS;

// include() the script where you want your comments to be..
//
include 'simple-comments.php';
//
// you might want to use a full path - see the notes inside simple-comments.php


// page ends, maybe with a footer or something..
//
echo '
</div>
</body>
</html>';


?>