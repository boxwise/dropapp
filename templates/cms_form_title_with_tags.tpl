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
		<br><span class="badge" style="background-color:#fff;color:#000">Status: {$data['statelabel']}</span>
    {/if}
</span>