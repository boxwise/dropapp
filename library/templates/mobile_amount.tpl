{if $box['id']}
	<p>Box <strong>{$box['box_id']}</strong> contains {$box['items']}x <strong>{$box['product']}</strong>
		<form method="get">
			<input type="hidden" name="saveamount" value="{$box['id']}">
			New amount of items in the box
			<input type="number" name="items" pattern="\d*" value="{$box['items']}">
			<input type="submit" value="Save new amount">
		</form>
{/if}
