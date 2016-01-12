<form id="listForm" class="form-inline forms_plugins_maillingchimp" method="post" action="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&tab=list&action=add">
    <div class="form-group">
        <label class="sr-only" for="list_id">{#list#|ucfirst}</label>
        <input type="text" class="form-control" id="list_id" name="list_id" placeholder="{#list#|ucfirst}">
    </div>
    <input type="submit" class="btn btn-primary" value="{#list_add#|ucfirst}" />
</form>