<div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
<style type="text/css" media="print">
   <!--
   @page { margin: 0; }
   -->
</style>

{foreach $data['labels'] as $d}
{if $data['fulllabel']}
<div class="boxlabel">
	<div class="boxlabel-qr"><img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://{$smarty.server.HTTP_HOST}{$settings['rootdir']}/mobile.php?barcode={$d['hash']}" /></div>
	<div class="boxlabel-title">{$currentOrg['label']}</div>
	
	<div class="boxlabel-field boxlabel-field-short">Box Number<span class="boxlabel-data">&nbsp;{$d['box_id']}</span></div>
	<div class="boxlabel-contents"> {$d['product']}</div>
	<div class="boxlabel-field boxlabel-field-contents">Contents</div>
	<div class="boxlabel-field boxlabel-field-left">Gender<span class="boxlabel-data">&nbsp;{$d['gender']}</span></div>
	<div class="boxlabel-field boxlabel-field-right">Size<span class="boxlabel-data">&nbsp;{$d['size']}</span></div>
	
</div>
{else}
<div class="boxlabel-small">
	<img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=http://{$smarty.server.HTTP_HOST}{$settings['rootdir']}/mobile.php?barcode={$d['hash']}" />
</div>
{/if}
{/foreach}
