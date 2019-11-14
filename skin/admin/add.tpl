{extends file="layout.tpl"}
{block name='head:title'}{#add_list#|ucfirst}{/block}
{block name='body:id'}mailchimp{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des listes">Mailchimp</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-xs-12 col-md-6">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{#add_list#|ucfirst}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    {include file="form/add.tpl" controller="contact"}
                </div>
            </section>
        </div>
    {/if}
{/block}