{extends file="layout.tpl"}
{block name='body:id'}plugins-{$pluginName}{/block}
{block name="article:content"}
    {include file="nav.tpl"}
    <h1>Mailling Chimp <small>- {#account_conf#|ucfirst}</small></h1>
    <!-- Notifications Messages -->
    {if isset($message)}
        <div class="mc-message clearfix">
            {include file="message-maillingchimp.tpl"}
        </div>
    {/if}
    {if isset($account)}
        <p class="lead">{#account#|ucfirst} : <strong>{$account}</strong> <a class="toggleModal" data-toggle="modal" data-target="#deleteModal" href="#"><span class="fa fa-trash-o"></span></a></p>
    {else}
        {include file="form/account.tpl"}
    {/if}

    {include file="modal/delete.tpl"}
{/block}
{block name='javascript'}
    {include file="js.tpl"}
{/block}