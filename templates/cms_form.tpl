<div class="content-form">
	<div class="row row-title">
		<div class="col-sm-12">
			<h1 id="form-title">{if $title}{$title}{else}{if !$data['id']}{$translate['cms_form_new']}{else}{$translate['cms_form_edit']}{/if}{/if}
			</h1> 
		</div>
	</div>

	<form id="cms_form" class="form form-horizontal areyousure" method="post" enctype="multipart/form-data"> 
		<input type="text" style="display:none" name="fakeloginautofill" />
		<input type="password" style="display:none" name="fakepassautofill" />

		<input type="hidden" name="id" value="{$data['id']}" />
		<input type="hidden" name="seq" value="{$data['seq']}" />
		<input type="hidden" name="_origin" value="{$smarty.get.origin|escape:'html'}" />
		{foreach $formelements as $element}
			{if !$element['tab'] and !$element['aside']}{include file="cms_form_{$element['type']}.tpl"}{/if}
		{/foreach}	

		{if $tabs}
			{if $tabs|count>1}
				<ul class="nav nav-tabs">
					{foreach $tabs as $key=>$value name="tabs"}
					<li {if $smarty.foreach.tabs.first}class="active"{/if}><a id="tabid_{$key}" href="#tab_{$key}" data-toggle="tab" style="z-index: {$tabs|count-$smarty.foreach.tabs.iteration}" {if $hiddentabs[$key]}class="hidden"{/if}> {$value}</a></li>
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

		<aside id="aside-container">
			<div class="affix aside-content">
				<div class="aside-form aside-form-top">
					{foreach $formelements as $element}		
						{if $element['asidetop']}{include file="cms_form_{$element['type']}.tpl"}{/if}
					{/foreach}	
				</div>
				{if !$data['hidesubmit']}
					<button name="__action" value="" class="btn btn-submit btn-success">{$translate['cms_form_submit']}</button>
				{/if}
				{foreach $formbuttons as $button}
					<button name="__action" value="{$button['action']}" class="btn btn-submit btn-success">{$button['label']}</button>
				{/foreach}

				{if !$data['hidecancel']}<a href="/?action={$smarty.get.origin|escape:'html'}" class="btn btn-cancel btn-default">{$translate['cms_form_cancel']}</a>{/if}
				
				<div class="aside-form">
					{foreach $formelements as $element}		
						{if $element['aside'] and !$element['asidetop']}{include file="cms_form_{$element['type']}.tpl"}{/if}
					{/foreach}	
				</div>
			</div>
		</aside>		
	</form>	
</div>
