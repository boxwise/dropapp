	<h2 class="page-header">Ordered boxes</h2>
	<table>
	{if $boxes|count == 0}
	<h3>All orders have been picked</h3>
	{else}
		{foreach $boxes as $b}
		{if $old!=$b['location']}<tr class="title"><td colspan=3><h3>{$b['location']}</h3></td></tr>{/if}
			<tr class="row">
				<td>{$b['box_id']}</td>
				<td>{$b['product']} {$b['gender']} {$b['size']} ({$b['items']}x)</td>
				<td class="button btn-list btn-list-small">
					<a class="btn" href="?vieworders&picked={$b['id']}">Picked</a>
					<a class="btn btn-small btn-warning" href="?vieworders&lost={$b['id']}">LOST</a>
				</td>
			</tr>
			{assign "old" $b['location']}
		{/foreach}
	{/if}
	</table>
