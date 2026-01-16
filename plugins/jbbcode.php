<?php
require_once __DIR__ . '/jbbcode/vendor/autoload.php';

use JBBCode\Parser;
use JBBCode\CodeDefinition;
use JBBCode\CodeDefinitionBuilder;
function parseBBCodeToHtml($bbcodeRaw)
{
    // $bbcode = htmlspecialchars($bbcodeRaw, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8');
    $bbcode = $bbcodeRaw;

    // Auto-detect YouTube video ID from URL
    $bbcode = preg_replace_callback('/\[youtube\](.*?)\[\/youtube\]/i', function ($m) {
        $url = trim($m[1]);
        if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $url)) {
            return "[youtube]".$url."[/youtube]";
        }
        if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([a-zA-Z0-9_-]{11})~', $url, $id)) {
            return "[youtube]{$id[1]}[/youtube]";
        }
        return '';
    }, $bbcode);
    // Remove " [font="..."]
    $bbcode = preg_replace_callback('/\[font=(["\'])(.*?)\1([^\]]*)\]/i', function($m) {return '[font="' . $m[2] . $m[3] . '"]';}, $bbcode);
    // Map BBCode [size=1..7]
    $bbcode = preg_replace_callback('/\[size\s*=\s*([1-7])\s*\]/i', function ($m) {
        $map = [
            1 => '10px',
            2 => '12px',
            3 => '14px',
            4 => '16px',
            5 => '18px',
            6 => '24px',
            7 => '32px',
        ];
        return '[size=' . $map[(int) $m[1]] . ']';
    }, $bbcode);

    $parser = new Parser();
    $parser->addCodeDefinitionSet(new \JBBCode\DefaultCodeDefinitionSet());

    // Tambahan [s], [strike]
    foreach (['s', 'strike'] as $tag) {
        $parser->addCodeDefinition((new CodeDefinitionBuilder($tag, '<s>{param}</s>'))->build());
    }

    // [center], [left], [right]
    foreach (['center', 'left', 'right', 'justify'] as $align) {
        $parser->addCodeDefinition((new CodeDefinitionBuilder($align, "<div style=\"text-align:$align\">{param}</div>"))->build());
    }

    // [quote]
    $parser->addCodeDefinition((new CodeDefinitionBuilder('quote', '<blockquote>{param}</blockquote>'))->build());

    // [code] escaped
    $builder = new CodeDefinitionBuilder('code', '<pre class="bg-dark-light rounded-xs p-1"><code class="text-nowrap">{param}</code></pre>');
    $parser->addCodeDefinition($builder->build());

    // [ul], [ol], [li]
    foreach (['ul', 'ol'] as $tag) {
        $parser->addCodeDefinition((new CodeDefinitionBuilder($tag, "<$tag>{param}</$tag>"))->build());
    }
    $parser->addCodeDefinition((new CodeDefinitionBuilder('li', '<li>{param}</li>'))->build());

    // [size], [color], [font]
    $parser->addCodeDefinition((new CodeDefinitionBuilder('size', '<span style="font-size:{option}">{param}</span>'))->setUseOption(true)->build());
    $parser->addCodeDefinition((new CodeDefinitionBuilder('color', '<span style="color:{option}">{param}</span>'))->setUseOption(true)->build());
    $parser->addCodeDefinition((new CodeDefinitionBuilder('font', '<span style="font-family:{option}">{param}</span>'))->setUseOption(true)->build());

    // [img]
    $parser->addCodeDefinition((new CodeDefinitionBuilder('img', '<img src="{param}" alt="Image" style="max-width:100%;">'))->build());
    // [youtube]
    $parser->addCodeDefinition(
        (new CodeDefinitionBuilder('youtube', '<iframe width="560" height="315" src="https://www.youtube.com/embed/{param}" frameborder="0" allowfullscreen></iframe>'))->build()
    );

    // [spoiler]
    $parser->addCodeDefinition((new CodeDefinitionBuilder('spoiler', '<details class="border border-dark border-1 shadow-m p-1 rounded-xs"><summary class="bg-dark-dark px-2">Spoiler</summary>{param}</details>'))->build());

    // [table], [th], [tr], [td]
    $parser->addCodeDefinition((new CodeDefinitionBuilder('table', '<table class="table table-borderless text-center rounded-sm shadow-l">{param}</table>'))->build());
    $parser->addCodeDefinition((new CodeDefinitionBuilder('tr', '<tr class="bg-gray-light">{param}</tr>'))->build());
    $parser->addCodeDefinition((new CodeDefinitionBuilder('th', '<th  scope="col" class="color-theme">{param}</th>'))->build());
    $parser->addCodeDefinition((new CodeDefinitionBuilder('td', '<td>{param}</td>'))->build());

    $parser->parse($bbcode);
    return $parser->getAsHtml();
}
?>