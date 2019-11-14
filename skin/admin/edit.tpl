{extends file="layout.tpl"}
{block name='head:title'}{#edit_list#}{/block}
{block name='body:id'}mailchimp{/block}

{block name='article:header'}
    <h1 class="h2"><a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}" title="Afficher la liste des listes">Mailchimp</a></h1>
{/block}
{block name='article:content'}
    {if {employee_access type="append" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header panel-nav">
                    <h2 class="panel-heading h5">{#edit_list#}</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">Membres</a></li>
                        <li role="presentation"><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuration</a></li>
                    </ul>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="general">
                            <p><strong>{#nb_members#}&nbsp;:&nbsp;{$members.total_items}</strong></p>
                            {include file="section/form/table-form-3.tpl" idcolumn='id' data=$members.members change_offset=true checkbox=false activation=false search=false sortable=false edit=false dlt=false controller="mailchimp"}
                        </div>
                        <div role="tabpanel" class="tab-pane" id="config">
                            <div class="row">
                                {include file="form/edit.tpl" controller="mailchimp"}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    {/if}
{/block}