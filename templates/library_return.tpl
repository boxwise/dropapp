<div class="content-form">
	<div class="row row-title">
		<div class="col-sm-12">
			<h1 id="form-title">{$title}
			</h1> 
		</div>
			{if $data['toolate']}<h1 class="col-md-6 col-sm-12"><span class="warning">Too late: rental time is {$data['duration']}</span></h1><br /><br />{/if}
		<h3 class="col-md-6 col-sm-12 fc">
</h3>
	</div>

<div class="fc"></div>
<a href="?action=library_edit&return={$data['id']}&user={$data['people_id']}" class="btn">Return the book</a>
</div>
