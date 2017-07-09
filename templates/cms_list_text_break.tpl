{if $column['bold']}<b>{/if}{if $column['italic']}<i>{/if} {$row[$column['field']]|strip_tags:false|truncate} {if $column['italic']}</i>{/if}{if $column['bold']}</b>{/if}<br /> 
{$row[$column['sec_line']]|strip_tags:false|truncate}{if $column['third_line']}<br/>
{$row[$column['third_line']]|strip_tags:false|truncate}{/if}
