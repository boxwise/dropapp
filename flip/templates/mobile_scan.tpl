	{if $box['id']}
		<h2 class="page-header">Box {$box['box_id']}</h2>
		Contains <strong>{$box['items']} {$box['product']}</strong><br />Move this box from <strong>{$box['location']}</strong> to:</p>
		{foreach $locations as $value=>$location}
			<a class="btn {if $location['selected']}disabled{/if}" href="?move={$box['id']}&location={$location['value']}">{$location['label']}</a><br />
		{/foreach}
	<hr></hr>
	<p>Change the amount of items in the box:</p>
		<form method="get">
			<input type="hidden" name="saveamount" value="{$box['id']}">
			<div class="form-group">
				<input type="number" name="items" pattern="\d*" value="{$box['items']}" class="form-control">			
			</div>
			<input class="btn" type="submit" value="Save new amount">
		</form>
	<hr></hr>

	<p>Or change the contents of the box</p>
	<a class="btn" href="?editbox={$box['id']}">Edit the box</a>
	{else}
		<p>This box is not found in the Drop Market.<br />
		<a class="btn" href="?newbox={$data['barcode']}">Create a new box</a><br />
		<a class="btn btn-light" href="?assignbox={$data['barcode']}">Link QR-code to a box</a><br />
		</p>
	{/if}
