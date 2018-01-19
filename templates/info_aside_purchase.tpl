<div class="info-aside" id="people_id_selected">
	<ul class="people-list">
	{foreach $data['people'] as $person}
		<li {if $person['parent_id']==0}class="parent"{/if}><a href="?action=people_edit&amp;id={$person['id']}">{$person['firstname']} {$person['lastname']} ({$person['age']} yr, {$person['gender']})</a>{if $person['comments']}<span class="people-comment">{$person['comments']}</span>{/if}</li>
	{/foreach}
	</ul>
	{if $data['shoeswarning']}<p class="warningbox">This resident has already bought<br />winter shoes or light shoes for men in this or the previous cycle.</p>{/if}
	<p class="familycredit"><i class="fa fa-tint"></i> <span id="dropcredit" data-drop-credit="{$data['dropcoins']}">{$data['dropcoins']}</span> {$translate['market_coins']} {if $data['allowdrops']}<a class="btn btn-sm" href="{$data['givedropsurl']}"><i class="fa fa-tint"></i> Give {$translate['market_coins']}</a>{/if}</p>
	{if $smarty.session.camp['food']}<p class="familycredit">Max food: <i class="fa fa-tint"></i> {$data['fooddrops']} drops</p>{/if}
	<p id="product_id_selected" class="hidden">Costs: <i class="fa fa-tint"></i> <span id="productvalue">0</span> {$translate['market_coins']}</p>
	<p id="not_enough_coins" class="hidden">This family has not enough {$translate['market_coins']} to make this purchase.</p>
</div>