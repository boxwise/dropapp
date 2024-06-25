<div class="form-group{if $element['hidden']} hidden{/if}" id="div_{$element['field']}" {if $element['listid']}data-listid="{$element['listid']}"{/if}>
    <input type="hidden" name="__{$element['field']}" value="text {if isset($element['format']) && $element['format']}{$element['format']}{/if} {if isset($element['readonly']) && $element['readonly']}readonly{/if}">
    
    <label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
    
    <div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if}">
        
        <div class="table-parent {if $element['allowsort']}sortable{/if} {if $element['allowmove']}zortable{/if}" data-table="tablename" data-startlevel="0" data-maxlevel="0" data-action="?action={$element['action']}" data-saveonchange="1" {if $element['maxheight']}data-maxheight="{$element['maxheight']}"{/if} {if $element['modal']}data-modal="true"{/if} data-inside-form="true">

            <div class="table-nav">
                <ul class="actions">
                    <li class="item-selected-independend">
                        <div class="btn-group">
                            {foreach $element['button'] as $code=>$button}
                                {if $button['showalways']}
                                    <button 
                                        data-operation="{$code}" 
                                        data-placement="top" 
                                        data-title="{$translate['cms_list_confirm_title']}" 
                                        data-btn-ok-label="{$translate['cms_list_confirm_ok']}" 
                                        data-btn-cancel-label="{$translate['cms_list_confirm_cancel']}" 
                                        class="start-operation btn btn-sm {if $button['confirm']}confirm{/if} btn-default" 
                                        {if isset($button['testid'])}data-testid="{$button['testid']}"{/if}
                                    >
                                        {if $button['icon']}
                                            <i class="fa {$button['icon']}"></i> 
                                        {/if}
                                        {$button['label']}
                                    </button>
                                {/if}
                            {/foreach}
                        </div>
                    </li>
                    {if $element['allowselectall']}<li>
                        <label class="btn btn-default btn-sm tooltip-this" data-toggle="tooltip" data-placement="top" title="{$translate['cms_list_selectall']}" for="group-select-1"><input id="group-select-1" type="checkbox" class="group-select"></label>
                    </li>{/if}
                    <li class="items-selected-dependent">
                        <div class="btn-group">
                        
                            {if $element['allowshowhide']}<button data-operation="show" class="start-operation btn btn-default btn-sm" href="#"><i class="fa glyphicon fa-eye"></i> {$element['show']}</button>
                            <button data-operation="hide" class="start-operation btn btn-default btn-sm" href="#"><i class="fa fa-eye-slash"></i> {$element['hide']}</button>{/if}
                            
                            {if $element['allowdelete']}<button data-operation="delete" data-placement="top" data-title="{$translate['cms_list_confirm_title']}" data-btn-ok-label="{$translate['cms_list_confirm_ok']}" data-btn-cancel-label="{$translate['cms_list_confirm_cancel']}" class="start-operation btn btn-sm confirm" href="#"><i class="fa fa-trash-o"></i> {$element['delete']}</button>{/if}
                            
                            {if $element['allowcopy']}<button data-operation="copy" data-placement="top" class="start-operation btn btn-sm btn-default" href="#"><i class="fa fa-copy"></i> {$element['copy']}</button>{/if}
                            
                            {foreach $element['button'] as $code=>$button}
                                {if !$button['showalways']}
                                    <button data-operation="{$code}" data-placement="top" class="start-operation btn btn-sm {if $button['confirm']}confirm{/if} btn-default {if $button['oneitemonly']}one-item-only{/if}" href="#">{if $button['icon']}<i class="fa {$button['icon']}"></i> {/if}{$button['label']}</button>
                                {/if}
                            {/foreach}
                        </div>
                    </li>
                    {if $element['allowadd']}
                    <li>
                        <a class="new-page item-add btn btn-sm btn-default" href="?action={if $element['addaction']}{$element['addaction']}{else}{$element['action']}_edit{/if}{if $element['modal']}&modal=1{/if}&origin={$element['origin']}"><i class="fa fa-plus"></i> {$element['add']}</a>
                    </li>
                    {/if}
                </ul>
                <ul class="navigations pull-right list-unstyled">
                    {if $listconfig['filter']}
                        <li>
                            <div class="btn-group">
                                <div type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown">
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
                                        <li><a href="?action={$listconfig['origin']}&filter={$key}">{$option}</a></li> 
                                    {/foreach}
                                </ul>
                            </div>
                        </li>
                    {/if}
                    {if $listconfig['search']}
                        <li>
                            <form method="post">
                                <div class="input-group form-inline search-group">                            
                                    <div class="has-feedback">
                                        <input type="text" class="form-control input-sm" name="search" value="{$listconfig['searchvalue']}">                                
                                        {if $listconfig['searchvalue']}<a class="fa fa-times form-control-feedback" href="?action={$listconfig['origin']}&resetsearch=true"></a>{/if}
                                    </div>
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="button"><span class="fa fa-search"></span></button>
                                    </span>
                                </div>
                            </form>
                        </li>
                    {/if}
                </ul>
                <div class="fc"></div>
            </div>  


            <div class="sticky-header-container">
                <table class="table" {if $listconfig['searchvalue']}">                                
                                        {if $listconfig['searchvalue']}<a class="fa fa-times form-control-feedback" href="?action={$listconfig['origin']}&resetsearch=true"></a>{/if}
                                    </div>
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-default" type="button"><span class="fa fa-search"></span></button>
                                    </span>
                                </div>
                            </form>
                        </li>
                    {/if}
                </ul>
                <div class="fc"></div>
            </div>  


            <div class="sticky-header-container">
                <table class="table" {if $element['sortlist']}data-sortlist="{$element['sortlist']}"{/if}>
                    <thead>
                        <tr>
                        {foreach $element['columns'] as $innerColumn}
                            <th {if isset($innerColumn['width'])} width="{$innerColumn['width']}"{/if} data-rowname="{$innerColumn}">{$innerColumn}</th>
                        {/foreach}
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $element['data'] as $row}
                            <tr data-id="{$row['id']}" data-level="0" class="item {if isset($row['visible']) and !$row['visible']}item-hidden{/if} level-0 
                                {if $element['allowedit'] or !isset($element['allowedit'])}item-clickable {/if}
                                {if $element['allowmove']}item-zortable{/if} 
                                {if $element['allowselect']}item-selectable{/if}">
                                {foreach $element['columns'] as $innerColumnKey => $innerColumn}
                                    {if $smarty.foreach.rows.first}
                                        <td class="controls-front list-level-0">
                                            <div class="td-content">
                                                <div class="handle"><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span></div>                        
                                                <label class="item-select-label"><input class="item-select" type="checkbox"></label>
                                                    {if $element['allowedit'] or !isset($element['allowedit'])}
                                                        <a class="td-content-field data-field-{$innerColumnKey}" href="?action={$element['action']}_edit{if $element['modal']}&modal=1{/if}&id={$row['id']}">
                                                    {else}
                                                        <p class="td-content-field data-field-{$innerColumnKey}">
                                                    {/if}
                                                    {$row[$innerColumnKey]|strip_tags:false|truncate}
                                                    {if $element['allowedit'] or !isset($element['allowedit'])}
                                                        </a>
                                                    {else}
                                                        </p>
                                                    {/if}
                                                <div class="parent-indent"></div>
                                            </div>
                                        </td>
                                    {elseif $innerColumnKey == 'countupdown'}
                                        <td class="list-level-0">
                                            <div class="td-content data-field-{$innerColumnKey}" data-sort=""><span class="data-field-{$innerColumnKey}-value">{$row[$innerColumnKey]|strip_tags:false|truncate}</span><span class="change-count-btns"><i class="change-count count-up fa fa-sort-up" aria-hidden="true" data-val="1"></i><i class="change-count count-down fa fa-sort-down" aria-hidden="true" data-val="-1"></i></span></div>
                                        </td>
                                    {else}
                                        <td class="list-level-0">
                                            <div class="td-content data-field-{$innerColumnKey}" data-sort="">{$row[$innerColumnKey]|strip_tags:false|truncate}</div>
                                        </td>
                                    {/if}

                                {/foreach}
                                
                            </tr>
                        {/foreach}
                    </tbody>
                </table>                
            </div>                    
            <fieldset></fieldset> <!-- all order changes made in zortable are saved here -->
        </div>          


            {if $element['tooltip']}{include file="cms_tooltip.tpl" valign=" middle"}{/if}
        </div>
    </div>

