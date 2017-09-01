<span class="hide">{$row[$column['field']]}</span>
<div class="need-indicator need-{$row['color']}">
	<i class="fa fa-{if $row['color']=='red'}sign-in{elseif $row['color']=='blue'}sign-out{elseif $row['color']=='green'}check{/if}"></i>&nbsp;{if $row['color']!='green'}{$row[$column['field']]|strip_tags:false|truncate|abs}{/if}
</div>
