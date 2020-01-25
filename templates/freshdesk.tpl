{literal}
<script>
  window.fwSettings={
    'widget_id':48000000566,
    'locale': 'en'
  };
  !function(){if("function"!=typeof window.FreshworksWidget){var n=function(){n.q.push(arguments)};n.q=[],window.FreshworksWidget=n}}()
</script>
<script type='text/javascript' src='https://widget.freshworks.com/widgets/48000000566.js' async defer></script>
{/literal}
<script>window.onload = function() {ldelim}FreshworksWidget('prefill','ticketForm',{ldelim}	name: '{$smarty.session.user.naam}',email: '{$smarty.session.user.email}',custom_fields: {ldelim} url: '{$smarty.server.REQUEST_URI}',user_id: '{$smarty.session.user.id}',organisation_id: '{$smarty.session.organisation.id}', organisation_label: '{$smarty.session.organisation.label}', {rdelim},{rdelim});{rdelim}</script>
