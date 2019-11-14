{mailchimp_conf}
<section class="section-block">
    <div class="container">
        <div class="row row-center">
            <div class="col-12 col-md-10 col-lg-9 col-xl-7">
                <div class="mailchimp">
                    <p class="h1 text-center">{#box_title#}</p>
                    <p class="lead text-center">{#box_text#}</p>
                    <form id="mailchimp-form" method="post" action="{$url}/{$lang}/mailchimp/" class="validate_form nice-form">
                        <div class="row">
                            <div class="col-12 col-xs-6">
                                <div class="form-group">
                                    <input id="firstname" type="text" name="firstname" placeholder="{#firstname#}" value="" class="form-control required" required/>
                                    <label for="firstname" class="is_empty">{#firstname#}&nbsp;*</label>
                                </div>
                            </div>
                            <div class="col-12 col-xs-6">
                                <div class="form-group">
                                    <input id="lastname" type="text" name="lastname" placeholder="{#lastname#}" value="" class="form-control required" required/>
                                    <label for="lastname" class="is_empty">{#lastname#}&nbsp;*</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input id="email" type="text" name="email" placeholder="{#email#}" value="" class="form-control required" required/>
                            <label for="email" class="is_empty">{#email#}&nbsp;*</label>
                        </div>
                        <div class="mc-message"></div>
                        <div class="text-center">
                            <p><input type="submit" class="btn btn-box btn-lg btn-sd-theme" value="{#mailchimp_validate_inscription#}"/></p>
                            <p><a href="{#private_policy_link#}" title="{#private_policy_title#}">{#private_policy_label#}</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>