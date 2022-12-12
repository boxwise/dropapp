<div class="row">
	<div class="col-sm-{$listconfig['width']}">
		<div class="row row-title">
			<div class="col-sm-12">
				<h1>{$title nofilter}</h1>
				{if $listconfig['haspagemenu']}
					<ul class="pagemenu list-unstyled" data-testid="listTab">
					{foreach $listconfig['pagemenu'] as $code=>$pagemenu}
						<li><a class="{if $pagemenu['active']}active{/if}" href="{$pagemenu['link']}" {if isset($pagemenu['testid'])}data-testid="{$pagemenu['testid']}"{/if} >{$pagemenu['label']}</a>
					{/foreach}
					</ul>
				{/if}
			</div>
		</div>
		<div class="table-parent {if $listconfig['tree']}list-tree{/if} {if $listconfig['allowsort']}sortable{/if} {if $listconfig['allowmove']}zortable{/if}" data-table="tablename" data-startlevel="{$listconfig['allowmovefrom']}" data-maxlevel="{if $listconfig['tree']}{$listconfig['allowmoveto']}{else}0{/if}" data-inheritvisibility="{$settings['inheritvisibility']}" data-action="{$listconfig['thisfile']}" data-saveonchange="1" {if $listconfig['maxheight']}data-maxheight="{$listconfig['maxheight']}"{/if}>

			<div class="table-nav">
				<ul class="actions">
					{if $listconfig['allowcollapse']}<li>
						<button class="btn btn-default btn-sm" id="collapseall" data-testid="collapseall"><i class="fa fa-chevron-circle-right"></i> Collapse All</button>
					</li>{/if}
					{if $listconfig['allowselectall']}<li>
						<label class="btn btn-default btn-sm tooltip-this" data-toggle="tooltip" data-placement="top" title="{$translate['cms_list_selectall']}" for="group-select-1"><input id="group-select-1" data-testid='select_all' type="checkbox" class="group-select"></label>
					</li>{/if}
					<li class="item-selected-independend">
						<div class="btn-group">
							{foreach $listconfig['button'] as $code=>$button}
								{if $button['showalways']}
									{if $button['options']}
										<div class="btn-group">
											<div type="button" class="btn btn-sm btn-default dropdown-toggle" {if isset($button['testid'])}data-testid="{$button['testid']}"{/if} >
												{if $button['icon']}
													<i class="fa {$button['icon']}"></i>
												{/if}
												Helo
												{if $button['image']}
													<img src="../assets/img/{$button['image']}" alt="not found"></img> 
												{/if}												
												{$button['label']} <span class="caret"></span>
											</div>
											<ul class="dropdown-menu pull-right button-multi" role="menu">
												{foreach $button['options'] as $key=>$option}
													<li>
														<a href="#" data-operation="{$code}" data-option="{$key}" class="start-operation {if $button['confirm']}confirm{/if}">
															{$option}
														</a>
													</li>
												{/foreach}
											</ul>
										</div>
									{else}
										<button data-operation="{if $button['link']}none{else}{$code}{/if}" data-placement="top" data-title="{$translate['cms_list_confirm_title']}" data-btn-ok-label="{$translate['cms_list_confirm_ok']}" data-btn-cancel-label="{$translate['cms_list_confirm_cancel']}" class="start-operation btn btn-sm {if $button['confirm']}confirm{/if} btn-default" href="{if $button['link']}{$button['link']}{else}#{/if}" {if isset($button['testid'])}data-testid="{$button['testid']}"{/if}>
											{if $button['icon']}
												<i class="fa {$button['icon']}"></i> 
											{/if}
											{$button['label']}</button>
									{/if}
								{/if}
							{/foreach}
						</div>
					</li>
					<li class="items-selected-dependent">
						<div class="btn-group">

							{if $listconfig['allowshowhide']}<button data-operation="show" class="action-show start-operation btn btn-default btn-sm" href="#" data-testid="list-show-button"><i class="fa glyphicon fa-eye"></i> {$listconfig['show']}</button>
							<button data-operation="hide" class="start-operation btn btn-default btn-sm" href="#" data-testid="list-hide-button"><i class="fa fa-eye-slash"></i> {$listconfig['hide']}</button>{/if}

							{if $listconfig['allowdelete']}<button data-operation="delete" data-placement="top" data-title="{$translate['cms_list_confirm_title']}" data-btn-ok-label="{$listconfig['delete']}" data-btn-cancel-label="{$translate['cms_list_confirm_cancel']}" class="action-delete start-operation btn btn-sm confirm" href="#" data-testid="list-delete-button"><i class="fa fa-trash"></i> {$listconfig['delete']}</button>{/if}

							{if $listconfig['allowcopy']}<button data-operation="copy" data-placement="top" class="action-copy start-operation btn btn-sm btn-default" href="#" data-testid="list-copy-button"><i class="fa fa-copy"></i> {$listconfig['copy']}</button>{/if}

							{foreach $listconfig['button'] as $code=>$button}
								{if !$button['showalways']}
									{if $button['options']}
										<div class="btn-group">
											<div type="button" class="btn btn-sm btn-default dropdown-toggle" {if isset($button['testid'])}data-testid="{$button['testid']}"{/if}>
												{if $button['icon']}
													<i class="fa {$button['icon']}"></i>
												{/if}
												{$button['label']} <span class="caret"></span></div>
											<ul class="dropdown-menu pull-right button-multi" role="menu">
												{foreach $button['options'] as $key=>$option}
													<li><a href="#" data-operation="{$code}" data-option="{$key}" class="start-operation {if $button['confirm']}confirm{/if}">{$option}</a></li>
												{/foreach}
											</ul>
										</div>
									{else}
										<button data-operation="{if $button['link']}none{else}{$code}{/if}" data-placement="top" data-title="{$translate['cms_list_confirm_title']}" data-btn-ok-label="{$translate['cms_list_confirm_ok']}" data-btn-cancel-label="{$translate['cms_list_confirm_cancel']}" class="start-operation btn btn-sm {if $button['confirm']}confirm{/if} btn-default {if $button['oneitemonly']}one-item-only{/if} {if $button['disableif']}disable-if{/if}" href="{if $button['link']}{$button['link']}{else}#{/if}" {if isset($button['testid'])}data-testid="{$button['testid']}"{/if}>
											{if $button['icon']}
												<i class="fa {$button['icon']}"></i> 
											{/if}
											{if $button['image']}
												<img src="../assets/img/{$button['image']}" class="{$button['imageClass']}"></img> 
											{/if}
											{$button['label']}</button>
									{/if}
								{/if}
							{/foreach}
						</div>
					</li>
					{if $listconfig['allowadd']}
					<li>
						<a class="new-page item-add btn btn-sm btn-default" data-testid = "NewItem" href="?action={if $listconfig['new']}{$listconfig['new']}{else}{$listconfig['edit']}{/if}&origin={$listconfig['origin']}"><i class="fa fa-plus"></i> {$listconfig['add']}</a>
					</li>
					{/if}
				</ul>
				<ul class="navigations pull-right list-unstyled">
					{include file="cms_list_filter.tpl"}
					{if $listconfig['search']}
						<li>
							<form method="post">
								<div class="input-group form-inline search-group">
									<div class="has-feedback">
										<input type="text" class="form-control input-sm" data-testid='box-search' name="search" value="{$listconfig['searchvalue']}">
										{if $listconfig['searchvalue']}<a class="fa fa-times-circle form-control-feedback" href="?action={$listconfig['origin']}&resetsearch=true"></a>{/if}
									</div>
									<span class="input-group-btn listSearchButtonDiv">
										<button class="btn btn-sm btn-default" data-testid = "search-button" type="submit"><span  class="fa fa-search"></span></button>
									</span>
								</div>
							</form>
						</li>
					{/if}
				</ul>
				<div class="fc"></div>
			</div>
			<div class="sticky-header-container">
				<table class="table list-{$listconfig['origin']}" {if $listconfig['sortlist']}data-sortlist="{$listconfig['sortlist']}"{/if} data-testid="table-{$listconfig['origin']}">
				  	<thead>
					  	<tr>
						{foreach $listdata as $key=>$column}
					  		<th {if $column['headerClass']}class="{$column['headerClass']}"{/if}><div {if $column['width']}style="width:{$column['width']}px;"{/if}>{$column['label'] nofilter}</div></th>
						{/foreach}
					  	</tr>
						<tr class="sorter-false firstline">
							{foreach $firstline as $column}
					  			<th><div>{$column nofilter}</div></th>
							{/foreach}
					  	</tr>
					</thead>
					<tbody>					
				    {foreach $data as $row}
						{* prepare parent_array for collapsing *}
						{if $listconfig['allowcollapse'] && $row['level']}
							{assign var="parent_array" value="-"|explode:$row['parent_id']}
						{/if}
				    	{if $prevlevel > $row['level'] && $listconfig['allowmove']}
				    		{while $prevlevel > $row['level']}
					    		<tr class="level-{$prevlevel} inbetween inbelow" data-level="{$prevlevel}"><td colspan="{$listdata|@count}"><span></span></td></tr>
									{assign var="prevlevel" value=$prevlevel-1}
				    		{/while}
				    	{/if}
					    {if $listconfig['allowmove']}<tr class="level-{$row['level']} inbetween" data-level="{$row['level']}"><td colspan="{$listdata|@count}"><span></span></td></tr>{/if}				    
							<tr id="row-{$row['id']}" data-id="{$row['id']}" data-level="{$row['level']}"
								class="item {if isset($row['visible']) and !$row['visible']}item-hidden{/if} level-{$row['level']}
								{if !$row['preventedit'] && ($listconfig['allowedit'][$row['level']] or !isset($listconfig['allowedit'])) && !isset($listconfig['listrownotclickable'])}item-clickable{/if}
								{if $row['preventdelete']}item-nondeletable{/if}
								{if $row['disableifistrue']}disable-if-is-true{/if}
								{if $listconfig['allowmove'] && $row['level']>=$listconfig['allowmovefrom'] && $row['level']<=$listconfig['allowmoveto']}item-zortable{/if}
								{if ($listconfig['allowselect']|is_array && $listconfig['allowselect'][$row['level']]) or (!$listconfig['allowselect']|is_array && $listconfig['allowselect'])}item-selectable{/if}
								{if $listconfig['allowcollapse'] && isset($row['level'])}overview-level-{$row['level']}{/if}
								{if $listconfig['allowcollapse'] && $row['level']}collapse{/if}"
								{* reference classes for collapse button *}
								{if $listconfig['allowcollapse'] && $row['level']} 
									{foreach $parent_array as $level=>$parent}
										{assign var="parent_array_slice" value=$parent_array|array_slice:0:($level+1)}
										data-hidecollapseparent{$level}={'-'|implode:$parent_array_slice}
									{/foreach}
									data-collapseparent={$row['parent_id']}
								{/if}>
								{foreach $listdata as $key=>$column name="rows"}
									{if $smarty.foreach.rows.first}
										<td class="controls-front list-level-{$row['level']} list-column-{$key} {if $listconfig['noindent']}no-indent{/if}">
											<div class="td-content">
												<div class="handle"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>
												{if $listconfig['allowcollapse']}
													<div class="collapsebutton" data-collapseid={$row['id']} data-notcollapsed={isset($row['notCollapsed'])}>
												</div>{/if}
												<label class="item-select-label">
													<input class="item-select" data-testid='select-id' type="checkbox" {if !$listconfig['allowselectinvisible'] && !$row['visible']}disabled{/if}>
												</label>
													{if !$row['preventedit'] and !isset($listconfig['allowedit'])}
														<a class="td-content-field" href="?action={$listconfig['edit']}&origin={$listconfig['origin']}&id={$row['id']}">
													{else}
														<p class="td-content-field">
													{/if}
													{include file="cms_list_{$column['type']}.tpl"}
													{if !$row['preventedit'] and !isset($listconfig['allowedit'])}
														</a>
													{else}
														</p>
													{/if}
												<div class="parent-indent"></div>
											</div>
										</td>
									{else}
										<td class="list-level-{$row['level']} list-column-{$key}">
											<div class="td-content">{include file="cms_list_{$column['type']}.tpl"}</div>
										</td>
									{/if}
								{/foreach}
							</tr>
				    	{assign var=prevlevel value=$row['level']}
				  	{/foreach}
					</tbody>
					{if $listfooter}
				  	<tfoot>
					  	<tr>
						{foreach $listfooter as $column}
					  		<th><div>{$column nofilter}</div></th>
						{/foreach}
					  	</tr>
					</tfoot>
					{/if}
				</table>
			</div>

			<fieldset></fieldset> <!-- all order changes made in zortable are saved here -->
		</div>
	</div>
</div>
