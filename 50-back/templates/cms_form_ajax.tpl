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
