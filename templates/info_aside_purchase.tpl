<div class="info-aside" id="people_id_selected">{$data['test']}
	<ul class="people-list">
	{foreach $data['people'] as $person}
		<li {if $person['id']==$data['person']}class="parent"{/if}><a href="?action=people_edit&amp;id={$person['id']}">{$person['firstname']} {$person['lastname']} ({$person['age']} yr, {$person['gender']})</a>{if $person['comments']}<span class="people-comment">{$person['comments']}</span>{/if}</li>
	{/foreach}
	</ul>

	{if $data['shoeswarning']}<p class="warningbox">This beneficiary has already bought<br />winter shoes or light shoes for men in this or the previous cycle.</p>{/if}

	{if ((isset($data['approvalsigned']) || {$smarty.get.origin} == "start") && {$data['approvalsigned']} == FALSE)}<a class="btn btn-danger" type="button" ><span class="fa fa-edit" href="?action=people_edit&amp;id={$person['id']}"> </span> Privacy declaration</a>{/if}


	<p class="familycredit"><img src="../assets/img/more_coins.png" class="coinsImage"></img> <span id="dropcredit" data-drop-credit="{$data['dropcoins']}">{$data['dropcoins']}</span> {$currency} {if $data['allowdrops']}<a class="btn btn-sm" href="{$data['givedropsurl']}"><img src="../assets/img/one_coin.png" class="coinsImage"></img> <span>Give {$currency}</span></a>{/if}<br /><br /><span class="small">Last purchase: {$data['lasttransaction']}</span>
	</p>
	{if $smarty.session.camp['food']}<p class="familycredit{if $data['fooddrops']<=0} warningbox{/if}">Max food: <img src="../assets/img/more_coins.png" class="coinsImage"></img></i> <span id="foodcredit"  data-food-credit="{$data['fooddrops']}">{$data['fooddrops']}</span> {$currency} <span class="people-comment">This is the amount of {$currency} that this family can spend on food items.</span></p>
	{/if}
	<p id="cart_value" class="hidden">Cart value: <img src="../assets/img/more_coins.png" class="coinsImage"> </img><span id="cartvalue_aside">0</span> {$currency}</p>
	<p id="not_enough_coins" class="hidden">This family has not enough {$currency} to make this purchase.</p>
</div>