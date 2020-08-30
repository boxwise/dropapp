{foreach $row[$column['field']] as $tag}
    <span class="badge" {if $tag['color']}style="background-color:{$tag['color']}"{/if}>{$tag['label']}</span>
{/foreach}