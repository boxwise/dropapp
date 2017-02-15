<div class="info-aside" id="people_id_selected">
	<p id="familyname">{$data['name']['container']} {$data['name']['lastname']}</p>
	<p>{$data['people']|@count} people</p>
	<ul class="people-list">
	{foreach $data['people'] as $person}
		<li {if $person['parent_id']==0}class="parent"{/if}><a href="?action=people_edit&amp;id={$person['id']}">{$person['firstname']} {$person['lastname']} ({$person['gender']}, {$person['age']})</a></li>
	{/foreach}
	</ul>
</div>