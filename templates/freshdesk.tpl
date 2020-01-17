{literal}
<script>
  window.fwSettings={
    'widget_id':48000000566,
    'locale': 'en'
  };
  !function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}()
</script>
<script type='text/javascript' src='https://widget.freshworks.com/widgets/48000000566.js' async defer></script>
<script> function FillWIdget($name,$email){FreshworksWidget('identify', 'ticketForm', {	name: $name,	email: $email,});};</script>
<!--<script> window.onload = function () {FreshworksWidget('identify', 'ticketForm', {	name: {$smarty.session.user.naam},	email: {$smarty.session.user.email},});}</script>	-->
{/literal}

<!--<li><a onclick="FreshworksWidget('identify', 'ticketForm', {	name: '{$smarty.session.user.naam}',	email: '{$smarty.session.user.email}',});" type="button" id = "Help" <!--href="http://helpme.boxwise.co"--> Help</button></li>-->
