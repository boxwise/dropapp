<div class="info-aside" id="people_id_selected" data-testid="info-aside">{$data['test']}
	<ul class="people-list">
		{foreach $data['people'] as $person name=peopleaside}
			{if $person@total > 1 && $person@first}
				<li class="highlight">Family Members</li>
			{/if}
			{if !$person['hide']}
				<li>
					<div class="people-info">
					{if !$data['hideprivatedata']}
						<a href="?action=people_edit&amp;id={$person['id']}" data-testid="familyMember">{if !$person['parent_id']}FH: {/if}{$person['firstname']} {$person['lastname']} ({if $person['age']}{$person['age']} yr,{/if} {$person['gender']})</a>
					{else}
						<div data-testid="familyMember">{if !$person['parent_id']}FH: {/if}{$person['firstname']} ({if $person['age']}{$person['age']} yr,{/if} {$person['gender']})</div>
					{/if}
					{if $person['taglabels'] || $person['comments']}
						{if $person['taglabels'] && !$data['hideprivatedata']}
							<div class="people-tags">	
								{foreach $person['tags'] as $tag}
									<span class="badge" {if $tag['color']}style="background-color:{$tag['color']};color:{$tag['textcolor']};"{/if}>{$tag['label']}</span>
								{/foreach}
							</div>
						{/if}
						{if $person['comments'] && !$data['hideprivatedata']}
							<span class="people-comment">{$person['comments']}</span>
						{/if}
					{/if}
					</div>
				</li>
			{/if}
		{/foreach}
	</ul>

	{if $data['shoeswarning']}<p class="warningbox">This beneficiary has already bought<br />winter shoes or light shoes for men in this or the previous cycle.</p>{/if}

	{if isset($data['approvalsigned']) && !$data['approvalsigned'] && $data['parent_id']==0 && !$data['hideprivatedata']} 
		<span class="privacyNoteSpan">Needs Data Privacy Agreement!</span>
		<a class="btn privacySignButton" data-toggle="tooltip" href="?action=people_edit&id={$data['people_id']}&active=signature" data-testid="privacyDeclarationMissingButton" >
			<span class="fa fa-edit"></span> 
				Sign
		</a>
	{/if}

	<hr />

	<p class="familycredit">
		<span id="dropcredit" data-drop-credit="{$data['dropcoins']}" data-testid="dropcredit">{$data['dropcoins']} {$currency} </span> 
	
	{if $data['allowdrops']}
		<a class="btn btn-sm" href="{$data['givedropsurl']}"><span data-testid='giveTokensButton'>Give {$currency}</span> <img src="../assets/img/one_coin.png" class="coinsImage" /></a>
	{/if}
	<br /><br />
	<span class="lastPurchaseSpan">Last purchase: {$data['lasttransaction']}</span>
	</p>
	{if $smarty.session.camp['food']}<p class="familycredit{if $data['fooddrops']<=0} warningbox{/if}">Max food: <img src="../assets/img/more_coins.png" class="coinsImage" /></i> <span id="foodcredit"  data-food-credit="{$data['fooddrops']}">{$data['fooddrops']}</span> {$currency} <span class="people-comment">This is the amount of {$currency} that this family can spend on food items.</span></p>
	{/if}
	<p id="cart_value" class="hidden">Cart value: <img src="../assets/img/more_coins.png" class="coinsImage" /> <span id="cartvalue_aside" data-testid="cartvalue_aside">0</span> {$currency}<br/>Remaining credit: <img src="../assets/img/more_coins.png" class="coinsImage"> </img><span id="creditvalue_aside">{$data['dropcoins']}</span> {$currency}</p>
	<p id="not_enough_coins" class="hidden">This family has not enough {$currency} to make this purchase.</p>

	<hr />
</div>
