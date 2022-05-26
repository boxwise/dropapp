<h2 class="page-header">Box {$box['box_id']}
	{if isset($box['taglabels'])}	
		{foreach $data['tags'] as $tag}
			<span class="badge" {if $tag['color']}style="background-color:{$tag['color']};color:{$tag['textcolor']};"{/if}>{$tag['label']}</span>
		{/foreach}
    {/if}
</h2>
	<div id ="box-info" class="container-fluid" data-testid="box-info">
		<div id="box-info-content" class = "row">
				<div id="box-info-product" class = "col-xs-12" data-testid="box-info-product">
					<strong>{$box['items']}x {$box['product2']}</strong>
				</div>
				<div id="box-info-gender" class = "col-xs-6" data-testid="box-info-gender">
					<strong>{$box['gender']}</strong>
				</div>
				<div id="box-info-size" class = "col-xs-6" data-testid="box-info-size">
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
	<div data-testid="moveBoxDiv">
	Move this box from <strong>{$box['location']}</strong> to:</p>
	</div>
	<div class="btn-list">
		{foreach $locations as $value=>$location}
			<a class="btn {if $location['selected']}disabled{/if}" href="?move={$box['id']}&location={$location['value']}">{$location['label']}</a>
		{/foreach}
	</div>
		
{if !$data['othercamp']}
		<hr></hr>
		<p>I took items out of this box:</p>
			<form method="get">
				<input type="hidden" name="saveamount" value="{$box['id']}">
				<div class="form-group">
					<input type="number" name="items" pattern="\d*" value="0" class="form-control">			
				</div>
				<input class="btn" type="submit" value="Remove these items">
			</form>
		<hr></hr>	
		<p>Change the contents of the box:</p>
		<a class="btn" href="?editbox={$box['id']}">Edit the box</a>
		<p>  <center>Box history</center></p>
		<div id="history">
			<center>{$history nofilter}</center>
		</div>
		
{/if}
{if $orders}
	<hr />
	<h2 class="page-header">View ordered boxes</h2>
	<a class="btn" href="?vieworders">{$orders} {if $orders==1} box is {else} boxes are {/if} ordered</a>
{/if}