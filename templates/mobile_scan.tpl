<h2 class="page-header">Box {$box['box_id']}</h2>
	<div id ="box-info" class="container-fluid">
		<div id="box-info-content" class = "row">
				<div id="box-info-product" class = "col-xs-12">
					<strong>{$box['items']}x {$box['product2']}</strong>
				</div>
				<div id="box-info-gender" class = "col-xs-6">
					<strong>{$box['gender']}</strong>
				</div>
				<div id="box-info-size" class = "col-xs-6">
					<strong>{$box['size']}</strong>
				</div>

		</div>
		{if $box['comments']}
			<div id="box-info-comment" class="row">
					{$box['comments']}
			</div>
		{/if}
		</div>
	</div>
	Move this box from <strong>{$box['location']}</strong> to:</p>
	<div class="btn-list">
		{foreach $locations as $value=>$location}
			<a class="btn {if $location['selected']}disabled{/if}" href="?move={$box['id']}&location={$location['value']}">{$location['label']}</a>
		{/foreach}
	</div>
		
{if !$data['othercamp']}
		<hr></hr>
		<p>I took items out of this box to the shop:</p>
			<form method="get">
				<input type="hidden" name="saveamount" value="{$box['id']}">
				<div class="form-group">
					<input type="number" name="items" pattern="\d*" value="0" class="form-control">			
				</div>
				<input class="btn" type="submit" value="Remove these items">
			</form>
		<hr></hr>	
		<p>Or change the contents of the box</p>
		<a class="btn" href="?editbox={$box['id']}">Edit the box</a>
		<p><a href="#" class="toggle-do">View Edit History</a></p>
		<div class="toggle-me hide" id="history">
			{$history}	
		</div>
		
{/if}
{if $orders}
	<hr />
	<h2 class="page-header">View ordered boxes</h2>
	<a class="btn" href="?vieworders">{$orders} boxes are ordered to the camp</a>
{/if}