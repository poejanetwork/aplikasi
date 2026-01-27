<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{$pagetitle} | {$settings.APP_NAME}</title>
	<link href="{$theme}/assets/css/bootstrap.min.css" rel="stylesheet" >
	 <link href="{$theme}/assets/fonts/css/fontawesome.min.css" rel="stylesheet" >
	<link href="{$theme}/assets/fonts/css/brands.min.css" rel="stylesheet" />
    <link href="{$theme}/assets/fonts/css/solid.min.css" rel="stylesheet" />
	<link href="{$theme}/assets/css/global.css" rel="stylesheet">
	<link href="{$theme}/assets/css/index.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

</head>
<body>
    
{if isset($content)}
    {include file=$content}
{else}
    <p>Content not found</p>
{/if}

<script src="https://code.jquery.com/jquery-4.0.0.min.js" integrity="sha256-OaVG6prZf4v69dPg6PhVattBXkcOWQB62pdZ3ORyrao=" crossorigin="anonymous"></script>
<script src="{$theme}/assets/js/bootstrap.bundle.min.js"></script>
<script src="{$theme}/assets/js/theme.min.js"></script>
{if isset($page_js)}
<script src="{$theme}/assets/js/pages/{$page_js}"></script>
{/if}

</body>
</html>