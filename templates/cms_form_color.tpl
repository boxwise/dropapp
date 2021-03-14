<div class="form-group" id="div_{$element['field']}">
    <label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
    <div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if} input-element {if $element['tooltip']}has-tooltip{/if}">
        <input id="colorPicker" class="form-control" name="{$element['field']}" value="{$data[$element['field']]}" />
    </div>
</div>