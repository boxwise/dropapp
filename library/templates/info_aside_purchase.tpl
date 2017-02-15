<div class="info-aside" id="people_id_selected">
	<p id="familyname">{$data['name']['firstname']} {$data['name']['lastname']}</p>
	<p>Adults: <span id="adults">{$data['adults']}</span>, children: <span id="children">{$data['children']}</span></p>
	<ul class="people-list">
	{foreach $data['people'] as $person}
		<li {if $person['parent_id']==0}class="parent"{/if}><a href="?action=people_edit&amp;id={$person['id']}">{$person['firstname']} {$person['lastname']} ({$person['gender']}, {$person['age']})</a>{if $person['comments']}<br /><span class="people-comment">{$person['comments']}</span>{/if}</li>
	{/foreach}
	</ul>
	<p class="familycredit"><i class="fa fa-tint"></i> <span id="dropcredit" data-drop-credit="{$data['dropcoins']}">{$data['dropcoins']}</span> drops {if $data['allowdrops']}<a class="btn btn-sm" href="{$data['givedropsurl']}"><i class="fa fa-tint"></i> Give drops</a>{/if}</p>
	<p id="product_id_selected" class="hidden">Costs: <i class="fa fa-tint"></i> <span id="productvalue">0</span> drops</p>
	<p id="not_enough_coins" class="hidden">This family has not enough Drop Coins to make this purchase.</p>
</div>