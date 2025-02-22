{if $listdata[$column['field']]['breakall']}<span class="breakall">{/if}
{$row[$column['field']]|strip_tags:false|truncate}
{if !empty($row['standard_product_id'])}
    <span class="list-toggle-value hide">{$row['standard_product_id']}</span>
    <span class="list-toggle inside-list-start-operation stay active">
        <span class="fa fa-circle-o"></span>
        <span class="fa fa-check-circle active" title="This product is part of the ASSORT standard"></span>
    </span>
{/if}
{if $listdata[$column['field']]['breakall']}</span>{/if}
