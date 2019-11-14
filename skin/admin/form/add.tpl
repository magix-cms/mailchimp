<form id="add_list" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=add" method="post" class="validate_form add_form collapse in">
    {if !empty($lists)}
    <div class="row">
        <div class="col-xs-12 col-md-6">
            <div class="form-group">
                <label for="list_id">{#list_id#}&nbsp;*</label>
                <select name="list_id" id="list_id" class="form-control required" required>
                    <option value="" disabled selected>{#select_list#}</option>
                    {foreach $lists as $list}
                        {if !in_array($list.id,$clists)}<option value="{$list.id}|{$list.name}">{$list.name}</option>{/if}
                    {/foreach}
                </select>
            </div>
        </div>
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
                            <input type="checkbox" id="lang_{$iso}" name="content[{$id}][active]" class="switch-native-control" />
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
    <div class="row">
        <div id="submit" class="col-xs-12 col-md-6">
            <button class="btn btn-main-theme pull-right" type="submit" name="action" value="add">{#save#|ucfirst}</button>
        </div>
    </div>
    {else}
        <p class="alert alert-info">{#must_create_list#}</p>
    {/if}
</form>