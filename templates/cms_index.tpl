{include file="cms_header.tpl"}

<nav class="pushy pushy-open visible-xs">
    <a href="#" class="pushy-close fa fa-times"></a>
    <ul class="level0">
    {foreach $menu as $item}
      <li class="nav-header">{$item['title']}</li>
      <li>
          <ul class="level1">
    {foreach $item['sub'] as $subitem}
            <li{if $subitem['active']} class="active"{/if}><a class="menu_{$subitem['include']}" href="?action={$subitem['include']}">{$subitem['title']}{if $subitem['alert']}<i class="fa fa-exclamation-circle"></i>{/if}</a></li>
      {/foreach}
          </ul>
      </li>
     {/foreach}
    </ul>
</nav>

<!-- Site Overlay -->
<div class="site-overlay"></div>

<!-- Your Content -->
<div id="container" {if $modal}class="modal-form"{/if}>
{if !$modal}{include file="cms_topmenu.tpl"}{/if}

  <div class="container-fluid">
  	{if !$modal}
	    <div class="nav-aside hidden-xs">
	      <ul class="level0">
	      {foreach $menu as $item}
	        <li class="nav-header">{$item['title']}</li>
	        <li>
	            <ul class="level1">
				{foreach $item['sub'] as $subitem}
	              <li{if $subitem['active']} class="active"{/if}><a class="menu_{$subitem['include']}" href="?action={$subitem['include']}">{$subitem['title']}{if $subitem['alert']}<i class="fa fa-exclamation-circle"></i>{/if}</a></li>
				  {/foreach}
	            </ul>
	        </li>
	       {/foreach}
	      </ul>
	    </div>
	{/if}
    <div class="content">
	    {if $include}{include file="{$include}"}{/if}
	    {if $include2}{include file="{$include2}"}{/if}
    </div>
  </div>
</div>

{include file="cms_footer.tpl"}