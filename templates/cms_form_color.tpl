<div class="form-group" id="div_{$element['field']}">
    <label for="field_{$element['field']}" class="control-label col-sm-2">{$element['label']}</label>
    <div class="col-sm-{if $element['width']>0 and $element['width']<11}{$element['width']}{else}6{/if} input-element {if $element['tooltip']}has-tooltip{/if}">
        <div id="colorPicker">
            <a class="color">
                <div class="colorInner"></div>
            </a>
            <div class="track"></div>
            <ul class="dropdown">
                <li></li>
            </ul>
            <input name="{$element['field']}" type="hidden" class="colorInput" value="{$data[$element['field']]}" />
        </div>
    </div>
</div>