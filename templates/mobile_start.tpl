	<h2 class="page-header" data-testid="mobileHeader">Search for a box</h2>
	<form method="get" action="">
		<div class="form-group">
			<input class="form-control" type="number" name="findbox" pattern="\d*" placeholder="Enter Box ID" value="" required autofocus>
		</div>
		<input type="submit" class="btn" value="Search">
	</form>
{if $orders}
	<hr />
	<h2 class="page-header">View ordered boxes</h2>
	<a class="btn" href="?vieworders">{$orders} {if $orders==1} box is {else} boxes are {/if} ordered</a>
{/if}