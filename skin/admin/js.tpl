{script src="/{baseadmin}/min/?g=charts" concat={$concat} type="javascript"}
{script src="/{baseadmin}/min/?f=plugins/{$pluginName}/js/admin.js" concat={$concat} type="javascript"}
<script type="text/javascript">
    $(function(){
        if (typeof MC_plugins_maillingchimp == "undefined")
        {
            console.log("MC_plugins_maillingchimp is not defined");
        }else{
            {if $smarty.get.getlang}
                MC_plugins_maillingchimp.runList(baseadmin,getlang);
            {/if}
        }
    });
</script>