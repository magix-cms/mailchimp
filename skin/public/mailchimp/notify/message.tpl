{if $message.type eq 'warning'}
    {$class = 'warning'}
    {$icon = 'exclamation-triangle'}
{elseif $message.type eq 'error'}
    {$class = 'danger'}
    {$icon = 'exclamation-triangle'}
{else}
    {$class = 'success'}
    {$icon = 'check'}
{/if}
<p class="alert alert-{$class} fade in">
    {*<button type="button" class="close" data-dismiss="alert">&times;</button>*}
    <span class="fa ico ico-{$icon}"></span> {$message.content}
</p>