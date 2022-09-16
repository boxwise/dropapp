	<h2 class="page-header">
	{if $box['id']}Edit box
		{if isset($box['taglabels'])}	
			{foreach $data['headertags'] as $tag}
				<span class="badge" {if $tag['color']}style="background-color:{$tag['color']};color:{$tag['textcolor']};"{/if}>{$tag['label']}</span>
			{/foreach}
    	{/if}
		{if isset($box['statelabel'])}	
			<nav class="navbar navbar-light bg-light">
				<span class="navbar-brand mb-0 h1">Status: 
				{if in_array($box['stateid'],[2,6])}	
				<span><span id="currentstate" style="color:red">{$box['statelabel']}</span> {$box['statemodified']}</span>
				{else}
				<span id="currentstate" style="color:green">{$box['statelabel']}</span>
				{/if}
				<span id="newstate"></span></span>
			</nav>
		{/if}
	{else}Make a new box{/if}</h2>
	<form method="post" action="?savebox=1">
		<input type="hidden" name="id" value="{$box['id']}">
		<input type="hidden" name="qr_id" value="{$box['qr_id']}">
		<input type="hidden" name="camp_id" value="{$smarty.session.camp['id']}">
		{if $box['box_id']}
			<div class="form-group">
				<input class="form-control" type="number" name="box_id" pattern="\d*" placeholder="Box Number" value="{$box['box_id']}" required readonly>
			</div>
		{/if}
		<div class="form-group">
			<select name="product_id" id="field_product_id" onchange="updateSizes(0)" class="form-control selectsearch {if $box['disabled']}select2-container select2-container-disabled{/if}" {if $box['disabled']}disabled{/if} required>
				<option value="">Select a product</option>
				{foreach $data['products'] as $p}<option value="{$p['value']}" data-sizegroup="{$p['sizegroup_id']}" {if $p['value']==$box['product_id']}selected{/if} >{$p['label']}</option>
				{/foreach}
			</select>
		</div>
		<div class="form-group">
			<select name="size_id" id="field_size_id" class="form-control" data-testid="size_id" {if $box['disabled']}disabled{/if} required>
				{if $data['sizes']}
					<option value="">Select a size</option>
					{foreach $data['sizes'] as $s}
						<option value="{$s['id']}" {if $s['id']==$box['size_id']}selected{/if}>{$s['label']}</option>
					{/foreach}
				{else}
					<option value="">First select a product</option>
				{/if}
			</select>
		</div>
		<div class="all-sizes hide">
			{foreach $data['allsizes'] as $as}
				<option value="{$as['id']}" class="sizegroup-{$as['sizegroup_id']}">{$as['label']}</option>
			{/foreach}
		</div>
		<div class="form-group">
			<select name="location_id" id="location_id" class="form-control" data-testid="location_id" {if $box['id'] !='new'}onchange="getNewBoxState()"{/if} required>
				<option value="">Select a location</option>
				{foreach $data['locations'] as $l}<option value="{$l['value']}" {if $l['value']==$box['location_id']}selected{/if}>{$l['label']}</option>
				{/foreach}
			</select>
		</div>
		<div class="form-group">
			<select name="tags[]" id="tags" class="form-control selectsearch" data-testid="tags" multiple {if $box['disabled']}disabled{/if} placeholder="Select tags">
				{if $data['tags']}
					{foreach $data['tags'] as $s}
						<option 
							value="{$s['value']}" 
							{if $s['selected']} selected{/if} 
						>{$s['label']}</option>
					{/foreach}
				{/if}
			</select>
		</div>
		<div class="form-group"><input type="number" id="items" name="items" pattern="\d*" placeholder="Number of items" value="{$box['items']}" class="form-control" min="0" data-testid="items_count" required {if $box['disabled']}readonly{/if}></div>
		<div class="form-group"><input type="text" id="comments" name="comments" placeholder="Comments for this box" value="{$box['comments']}" data-testid="comments" class="form-control"  {if $box['disabled']}readonly{/if}></div>
		<hr/>
		<center>
		{if $box['id'] != 'new'}
		<div class="form-group" id="checkbox">
			<div class="col-sm-6">
				<label for="lost" class="checkbox">
				<input type="checkbox" id="lost" name="lost" value="1" onclick="setBoxState('lost')"
					{if $box['lost']}checked{/if}
				/> 
					I can’t find this box
				</label>
			</div>
			<div class="col-sm-6">
				<label for="scrap" class="checkbox">
				<input type="checkbox" id="scrap" name="scrap" value="1" onclick="setBoxState('scrap')" 
					{if $box['scrap']}checked{/if}
				/> 
					Scrap this box?
				</label>
			</div>
		</div>
		{/if}
		</center>
		<hr/>


		<input type="submit" name="submitbutton" class="btn" value="Save" data-testid="submit_new_box">
	</form>
{* 	<script>{if $box['product_id']}getSizes({$box['size_id']});{/if}</script> *}