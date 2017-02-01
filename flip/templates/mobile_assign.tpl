<h2 class="page-header">Link QR to box</h2>
<form method='get'>
	<input type="hidden" name="saveassignbox" value="{$data['barcode']}">
	<div class="form-group">
		<select name="box" class="selectsearch form-control">
			<option value="">Select a box</option>
			{foreach $data['stock'] as $s}
				<option value="{$s['id']}">{$s['label']}</option>
			{/foreach}
		</select>		
	</div>
	<input class="btn" type="submit" value="Save" />
</form>