{if $listconfig['filter']}
	<li>
		<div class="btn-group">
			<div type="button" class="btn btn-sm btn-default dropdown-toggle {if $listconfig['filtervalue']}filter-applied{/if}" data-toggle="dropdown" data-testId="filter1Button">
				{if $listconfig['filtervalue']}
					{$listconfig['filter']['options'][$listconfig['filtervalue']]}
				{else}
					{$listconfig['filter']['label']}
				{/if}
				{if $listconfig['filtervalue']}
					<a class="fa fa-times form-control-feedback" href="?action={$listconfig['origin']}&resetfilter=true"></a>
				{else}
					<span class="caret"></span>
				{/if}
			</div>
			<ul class="dropdown-menu pull-right" role="menu">
				{foreach $listconfig['filter']['options'] as $key=>$option}
					<li><a href="?action={$listconfig['origin']}&filter={$key}">{$option}</a></li></li> 
				{/foreach}
			</ul>
		</div>
	</li>
{/if}

{if $listconfig['filter2']}
	<li>
		<div class="btn-group">
			<div type="button" class="btn btn-sm btn-default dropdown-toggle {if $listconfig['filter2value']}filter-applied{/if}" data-toggle="dropdown" data-testId="filter2Button">
				{if $listconfig['filter2value']}
					{$listconfig['filter2']['options'][$listconfig['filter2value']]}
				{else}
					{$listconfig['filter2']['label']}
				{/if}
				{if $listconfig['filter2value']}
					<a class="fa fa-times form-control-feedback" href="?action={$listconfig['origin']}&resetfilter2=true"></a>
				{else}
					<span class="caret"></span>
				{/if}
			</div>
			<ul class="dropdown-menu pull-right" role="menu">
				{foreach $listconfig['filter2']['options'] as $key=>$option}
					<li><a href="?action={$listconfig['origin']}&filter2={$key}">{$option}</a></li></li> 
				{/foreach}
			</ul>
		</div>
	</li>
{/if}
{if $listconfig['filter3']}
	<li>
		<div class="btn-group">
			<div type="button" class="btn btn-sm btn-default dropdown-toggle {if $listconfig['filter3value']}filter-applied{/if}" data-toggle="dropdown" data-testId="filter3Button">
				{if $listconfig['filter3value']}
					{$listconfig['filter3']['options'][$listconfig['filter3value']]}
				{else}
					{$listconfig['filter3']['label']}
				{/if}
				{if $listconfig['filter3value']}
					<a class="fa fa-times form-control-feedback" href="?action={$listconfig['origin']}&resetfilter3=true"></a>
				{else}
					<span class="caret"></span>
				{/if}
			</div>
			<ul class="dropdown-menu pull-right" role="menu">
				{foreach $listconfig['filter3']['options'] as $key=>$option}
					<li><a href="?action={$listconfig['origin']}&filter3={$key}">{$option}</a></li></li> 
				{/foreach}
			</ul>
		</div>
	</li>
{/if}

{if $listconfig['filter4']}
	<li>
		<div class="btn-group">
			<div type="button" title="Chose among Products existing in boxes" class="btn btn-sm btn-default dropdown-toggle {if $listconfig['filter4value']}filter-applied{/if}" data-toggle="dropdown">
				{if $listconfig['filter4value']}
					{$listconfig['filter4']['options'][$listconfig['filter4value']]}
				{else}
					{$listconfig['filter4']['label']}
				{/if}
				{if $listconfig['filter4value']}
					<a class="fa fa-times form-control-feedback" href="?action={$listconfig['origin']}&resetfilter4=true"></a>
				{else}
					<span class="caret"></span>
				{/if}
			</div>
			<ul class="dropdown-menu pull-right" role="menu">
				{foreach $listconfig['filter4']['options'] as $key=>$option}
					<li><a href="?action={$listconfig['origin']}&filter4={$key}">{$option}</a></li></li> 
				{/foreach}
			</ul>
		</div>
	</li>
{/if}
