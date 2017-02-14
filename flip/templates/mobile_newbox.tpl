	<h2 class="page-header">{if $box['id']}Edit box{else}Make a new box{/if}</h2>
	<form method="post" action="?savebox={$box['qr_id']}">
		<input type="hidden" name="id" value="{$box['id']}">
		<input type="hidden" name="qr_id" value="{$box['qr_id']}">
		{if $box['id']}
			<div class="form-group">
				<input class="form-control" type="number" name="box_id" pattern="\d*" placeholder="Box Number" value="{$box['box_id']}" required>
			</div>
		{/if}
		<div class="form-group">
			<select name="product_id" id="field_product_id" onchange="getSizes(0)" class="form-control selectsearch" required>
				<option value="">Select a product</option>
				{foreach $data['products'] as $p}<option value="{$p['value']}" {if $p['value']==$box['product_id']}selected{/if}>{$p['label']}</option>
				{/foreach}
			</select>
		</div>
		<div class="form-group">
			<select name="size_id" id="field_size_id" class="form-control" required>
				<option value="">Select a size</option>
			</select>
		</div>
		<div class="form-group">
			<select name="location_id" class="form-control" required>
				<option value="">Select a location</option>
				{foreach $data['locations'] as $l}<option value="{$l['value']}" {if $l['value']==$box['location_id']}selected{/if}>{$l['label']}</option>
				{/foreach}
			</select>
		</div>
		<div class="form-group"><input type="number" name="items" pattern="\d*" placeholder="Number of items" value="{$box['items']}" class="form-control" required></div>
		<div class="form-group"><input type="text" name="comments" placeholder="Comments for this box" value="{$box['comments']}" class="form-control"></div>
		<input type="submit" class="btn" value="Save">
	</form>
	<script>{if $box['product_id']}getSizes({$box['size_id']});{/if}</script>