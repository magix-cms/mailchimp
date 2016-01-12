<nav class="navbar navbar-default">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
            <span class="fa fa-bar"></span>
        </button>
        <a href="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&amp;tab=account" class="navbar-brand">Mailling Chimp</a>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
            <li>
                <a href="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&amp;tab=account"><span class="fa fa-cog"></span> {#account_conf#|ucfirst}</a>
            </li>
            {if isset($account)}
            <li class="dropdown">
                <a data-toggle="dropdown" class="dropdown-toggle" href="#">{#account_list#|ucfirst} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                    {foreach $array_lang as $key => $value nocache}
                        <li>
                            <a href="{$pluginUrl}&amp;getlang={$key}&amp;tab=list&amp;action=list">
                                {$value|upper}
                            </a>
                        </li>
                    {/foreach}
                </ul>
            </li>
            {/if}
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li class="pull-right">
                <a href="{$pluginUrl}&amp;getlang={$smarty.get.getlang}&amp;tab=about"><span class="fa fa-info-circle"></span> {#plugin_about#|ucfirst}</a>
            </li>
        </ul>
    </div>
</nav>