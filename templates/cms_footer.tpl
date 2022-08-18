    <script src="/assets/js/moment-with-locales.min.js"></script>
    <script src="/assets/js/minified.js"></script>
    <script src="/assets/js/jquery-ui-1.10.4.custom.min.js"></script>
    <script src="/assets/js/jquery.tablesorter.min.js"></script>
    <script src="/assets/js/jquery.tablesorter.widgets.js"></script>
    <script src="/assets/js/jquery.ui.touch-punch.min.js"></script>
    <script src="/assets/jsignature/jquery.signature.js"></script>

    <script src="/assets/js/magic.js?v=9"></script>
    <script src="/assets/js/custom.js?v=9"></script> 
    <script src="/assets/js/shoppingCart.js"></script>    

	  {if $smarty.session.user}
      {include file="freshdesk.tpl"}
    {/if}
    {if $notification}
      {literal}
      <script language="javascript">
        $(document).ready(function () {
          var n = noty({
              type: "information",
              text: "{/literal}{$notification}{literal}",
          });
        });
      </script>
      {/literal}
    {/if}
  </body>
</html>