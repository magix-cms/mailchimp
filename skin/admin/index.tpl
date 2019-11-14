{extends file="layout.tpl"}
{block name='head:title'}mailchimp{/block}
{block name='body:id'}mailchimp{/block}
{block name='article:header'}
    {if {employee_access type="append" class_name=$cClass} eq 1 && isset($api.api_key)}
        <div class="pull-right">
            <p class="text-right">
                {#nbr_list#|ucfirst}: {$lists|count}<a href="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" title="{#add_list#}" class="btn btn-link">
                    <span class="fa fa-plus"></span> {#add_list#|ucfirst}
                </a>
            </p>
        </div>
    {/if}
    <h1 class="h2">Mailchimp</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header panel-nav">
                    <h2 class="panel-heading h5">Gestion des listes</h2>
                    <ul class="nav nav-tabs" role="tablist">
                        {if isset($api.api_key)}<li role="presentation" class="active"><a href="#general" aria-controls="general" role="tab" data-toggle="tab">Listes</a></li>{/if}
                        <li role="presentation"{if !isset($api.api_key)} class="active"{/if}><a href="#config" aria-controls="config" role="tab" data-toggle="tab">Configuration</a></li>
                    </ul>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    <div class="tab-content">
                        {if isset($api.api_key)}<div role="tabpanel" class="tab-pane active" id="general">
                            {include file="section/form/table-form-3.tpl" idcolumn='id_list' data=$lists activation=false sortable=false controller="mailchimp"}
                        </div>{/if}
                        <div role="tabpanel" class="tab-pane{if !isset($api.api_key)} active{/if}" id="config">
                            <div class="row">
                                <form id="edit_api" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$api.id_api}" method="post" class="validate_form{if !isset($api.api_key)} add_form{/if} col-ph-12 col-md-4">
                                    <div>
                                        <div class="form-group">
                                            <label for="api_key">{#api_key#|ucfirst}</label>
                                            <input type="text" name="api_key" id="api_key" class="form-control" placeholder="{#api_key#}" value="{$api.api_key}" />
                                        </div>
                                    </div>
                                    <div id="submit">
                                        <input type="hidden" id="id_api" name="id_api" value="{$api.id_api}">
                                        <button class="btn btn-main-theme pull-right" type="submit" name="edit_type" value="{if !isset($api.api_key)}add{else}update{/if}">{#save#|ucfirst}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        {include file="modal/delete.tpl" data_type='list' title={#modal_delete_title#|ucfirst} info_text=true delete_message={#delete_list_message#}}
        {include file="modal/error.tpl"}
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}