<div class="content-form">
	<div class="row row-title">
		<div class="col-sm-12">
			<h1 id="form-title">{$title}
			</h1> 
		</div>
		<h3 class="col-md-6 col-sm-12">
{if $data['category_id']==2}Make sure that the resident brings back the item in good condition. If user has no items borrowed anymore, you can give back their ID.{/if}
{if $data['category_id']==1}Make sure that the user brings back the bicycle in good condition, that they locked it correctly and that you get key, front light, reflective vest and helmet back. After checking all this, give the user their Bike Certificate card back.{/if}
</h3>
	</div>

<div class="fc"></div>
<a href="?action=borrow_edit&return={$data['id']}&user={$data['people_id']}" class="btn btn-success">Return the item</a>
</div>
