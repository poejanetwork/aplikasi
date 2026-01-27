{include file="header.tpl"}

{if isset($content)}
    {include file=$content}
{else}
    <p>Content not found</p>
{/if}

{include file="footer.tpl"}