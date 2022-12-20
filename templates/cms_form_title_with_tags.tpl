{$titlewithtags nofilter}
<span style="text-transform:none;">
    {if isset($data['age'])}
        ({$data['age']} yr{if isset($data['gender'])}, {$data['gender']}{/if})
    {elseif isset($data['gender'])}
        ({$data['gender']})
    {/if}
    {if isset($data['taglabels'])}	
        {foreach $data['tags'] as $tag}
            <span class="badge" {if $tag['color']}style="background-color:{$tag['color']};color:{$tag['textcolor']};"{/if}>{$tag['label']}</span>
        {/foreach}
    {/if}
    {if isset($data['statelabel'])}	
        <nav class="navbar navbar-light bg-light">
            <span class="navbar-brand mb-0 h1">Status: 
            {if in_array($data['stateid'],[2,6])}	
            <span id="currentstate" style="color:red">{$data['statelabel']}</span>
            {else}
            <span id="currentstate" style="color:green">{$data['statelabel']}</span>
            {/if}
            <span id="newstate"></span></span>
        </nav>
    {/if}
</span>