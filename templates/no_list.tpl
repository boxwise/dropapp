<div class="content-form">
	<div class="row row-title">
		<div class="col-sm-12">
			<h1 id="form-title">{if $title}{$title}{else}{if !$data['id']}{$translate['cms_form_new']}{else}{$translate['cms_form_edit']}{/if}{/if}
			</h1>
			<p class="title-details">{if $adults}{$translate['adults']}: {$adults}{/if}{if $children}, {$translate['children']}: {$children}{/if}<br />
			{if $container}{$translate['container']}: {$container}{/if}</p>
			{if $dropcoins}<p class="dropcoins"><img src="../assets/img/more_coins.png" class="coinsImage"></img> <span class="dropcoins-text"><b>{$dropcoins}</b><br />{$currentcamp['currencyname']}</span></p>{/if}
		</div>
	</div>

	<form class="form form-horizontal areyousure" method="post">

		<input type="hidden" name="id" value="{$data['id']}" />
		<input type="hidden" name="seq" value="{$data['seq']}" />
		<input type="hidden" name="_origin" value="{$smarty.get.origin}" />
		{foreach $formelements as $element}
			{if !$element['tab'] and !$element['aside']}{include file="cms_form_{$element['type']}.tpl"}{/if}
		{/foreach}	

		{if $tabs}
			{if $tabs|count>1}
				<ul class="nav nav-tabs">
					{foreach $tabs as $key=>$value name="tabs"}
					<li {if $smarty.foreach.tabs.first}class="active"{/if}><a href="#tab_{$key}" data-toggle="tab">{$value}</a></li>
					{/foreach}
				</ul>
			{/if}
			
			<div class="tab-content">
				{foreach $tabs as $key=>$value name="tabcontent"}
				<div class="tab-pane fade {if $smarty.foreach.tabcontent.first}in active{/if}" id="tab_{$key}">
				
					{foreach $formelements as $element}
						{if $element['tab'] and $element['tab']==$key and !$element['aside']}{include file="cms_form_{$element['type']}.tpl"}{/if}
					{/foreach}	
					
				</div>
				{/foreach}
			</div>
		{/if}
	</form>	
</div>
