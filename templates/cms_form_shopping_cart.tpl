<div id="shopping_cart_outer_div" class="hidden">
    <h2>Shopping cart</h2>
    <div class="table-nav sticky-header-container col-sm-10 shopping_cart_table">
        <table class="table initialized" id="shopping_cart" data-testid="shopping_cart">
        <thead>
            <tr>
            {foreach $element['columns'] as $key=>$column}
                <th {if $column['width']} width="{$column['width']}{/if}" data-rowname="{$key}">{$column['name']}</th>
            {/foreach}
            </tr>
        </thead>
        </table>
    </div>
</div>