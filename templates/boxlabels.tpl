<div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>
<style type="text/css" media="print">
   <!--
   @page { margin: 0; }
   -->
</style>

{foreach $data['labels'] as $d}
<div class="boxlabel-small" data-testid="boxlabel-small">
   <a href="{$d['data-testurl']}">
	   <img src="{$d['qrPng']}" />
   </a>
</div>
{/foreach}
