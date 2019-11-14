<form id="edit_list" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit&edit={$list.id_list}" method="post" class="validate_form edit_form col-ph-12">
    <p>{#list_id#|ucfirst}&nbsp;<strong>{$list.list_id}</strong></p>
    {include file="language/brick/dropdown-lang.tpl"}
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            <div class="tab-content">
                {foreach $langs as $id => $iso}
                    <fieldset role="tabpanel" class="tab-pane{if $iso@first} active{/if}" id="lang-{$id}">
                        <div class="form-group">
                            <div class="form-group">
                                <label for="content[{$id}][name_list]">{#name_list#}&nbsp;</label>
                                <input type="text" name="content[{$id}][name_list]" id="content[{$id}][name_list]" class="form-control" value="{$list.content[$id].name_list}"/>
                            </div>
                        </div>
                    </fieldset>
                {/foreach}
            </div>
            <fieldset>
                <legend>{#active_for#}</legend>
                <div class="row">
                    {foreach $langs as $id => $iso}
                        <div class="col-ph-12 col-xs-6 col-sm-4 col-md-3 col-lg-2">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="lang_{$iso}">{$iso|upper}&nbsp;?</label>
                                    <div class="switch">
                                        <input type="checkbox" id="lang_{$iso}" name="content[{$id}][active]" class="switch-native-control"{if $list.content[$id].active} checked{/if}/>
                                        <div class="switch-bg">
                                            <div class="switch-knob"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                </div>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div id="submit" class="col-xs-12 col-md-6">
            <input type="hidden" id="id_list" name="id" value="{$list.id_list}">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
        </div>
    </div>
</form>