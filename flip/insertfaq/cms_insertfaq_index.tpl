{include file="cms_insertfaq_header.tpl"}

<div id="container">
	<div class="container-fluid">
		<select name="faq_cat" id="faq_cat">
			<option value="">Kies een onderwerp...</option>
			{foreach $categories as $category}
				<option value="faq{$category['id']}">{$category['title']}</option>
			{/foreach}
		</select>
	</div>
</div>

{include file="cms_insertfaq_footer.tpl"}