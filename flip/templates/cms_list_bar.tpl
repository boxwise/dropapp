<span class="hide">{($row['result']*100)|intval}</span>
<div class="bar stay">
	<div class="bar-inside {if $row['result'] < 0.2}bar-inside-red{elseif $row['result'] < 0.35}bar-inside-orange{elseif $row['result'] < 0.7}bar-inside-yellow{elseif $row['result'] < 1.3}bar-inside-green{else}bar-inside-blue{/if}" 
		style="{if $row['result']>1}left:50%;width:{(($row['result']-1)*50)|intval}px{else}left:{(($row['result'])*50)|intval}px;width:{((1-$row['result'])*50)|intval}px;{/if}">
	</div>
	<div class="from-left bar-inside {if $row['result'] < 0.2}bar-inside-red{elseif $row['result'] < 0.35}bar-inside-orange{elseif $row['result'] < 0.7}bar-inside-yellow{elseif $row['result'] < 1.3}bar-inside-green{else}bar-inside-blue{/if}" 
		style="width:{($row['result']*100)|intval}%">
	</div>
	<div class="bar-value">{($row['result']*100)|intval}%</div>
</div>