<form id="apiForm" class="form-inline forms_plugins_mailchimp" method="post" action="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&tab=account&action=save">
    <div class="form-group">
        <label class="sr-only" for="account_api">{#account#|ucfirst}</label>
        <input type="text" class="form-control" id="account_api" name="idapi" placeholder="{#account#|ucfirst}">
    </div>
    <input type="submit" class="btn btn-primary" value="{#account_save#|ucfirst}" />
</form>