<form id="mailchimp-form" method="post" action="{$smarty.server.REQUEST_URI}" class="form-horizontal">
    <div class="form-group">
        <label class="col-md-3" for="lastname">
            {#lastname#|ucfirst}* :
        </label>
        <div class="col-md-6">
            <input id="lastname_chimp" type="text" name="lastname_chimp" value="" class="form-control"  />
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3" for="firstname">
            {#firstname#|ucfirst}* :
        </label>
        <div class="col-md-6">
            <input id="firstname_chimp" type="text" name="firstname_chimp" value="" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-3" for="email">
            {#email#|ucfirst}* :
        </label>
        <div class="col-md-6">
            <input id="email_chimp" type="text" name="email_chimp" value="" class="form-control" />
        </div>
    </div>
    <div class="form-group">
        <p class="col-md-3">
            &nbsp;
        </p>
        <div class="col-md-6">
            <input type="submit" class="btn btn-primary" value="{#save#|ucfirst}" />
        </div>
    </div>
    <div class="mc-message"></div>
</form>