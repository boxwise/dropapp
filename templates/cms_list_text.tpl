{if $listdata[$column['field']]['breakall']}<span class="breakall">{/if}
{$row[$column['field']]|strip_tags:false|truncate}
{if $listdata[$column['field']]['breakall']}</span>{/if}
