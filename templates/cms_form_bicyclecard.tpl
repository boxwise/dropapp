	{if $data['picture']}
	<div class="form-group" id="div_printcertificate">
		<label for="field" class="control-label col-sm-2"></label>
		<div class="col-sm-6">
	 		<a href="/pdf/bicyclecard.php?id={$data['id']}" target="_blank" class="btn btn-success">Print Bicycle Certificate</a>
		</div>
	</div>
	{/if}