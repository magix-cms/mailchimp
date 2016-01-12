{extends file="layout.tpl"}
{block name='body:id'}plugins-{$pluginName}{/block}
{block name="styleSheet" append}
    {include file="css.tpl"}
{/block}
{block name="article:content"}
    {include file="nav.tpl"}
    <h1>{#h1_list_cat_link#}</h1>
    {include file="list.tpl"}
{/block}
{block name="javascript"}
    {include file="js.tpl"}
{/block}
{block name="modal"}
    <div id="window-dialog"></div>
{/block}