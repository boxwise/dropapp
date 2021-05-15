{if $smarty.server.HTTP_HOST == 'app.boxtribute.org' || $smarty.server.HTTP_HOST == 'demo.boxtribute.org' || $smarty.server.HTTP_HOST == 'staging.boxtribute.org'}
<script>
    const heapProjectId = {($smarty.server.HTTP_HOST == 'app.boxtribute.org') ? '1677886010' : '17989829' };
    const userId = "{$smarty.session.user.id}";
    const organisationId = "{$smarty.session.organisation.label}";
    const isAdmin = "{$smarty.session.user.is_admin}";

    {literal}
    window.heap=window.heap||[],heap.load=function(e,t){window.heap.appid=e,window.heap.config=t=t||{};var r=t.forceSSL||"https:"===document.location.protocol,a=document.createElement("script");a.type="text/javascript",a.async=!0,a.src=(r?"https:":"http:")+"//cdn.heapanalytics.com/js/heap-"+e+".js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(a,n);for(var o=function(e){return function(){heap.push([e].concat(Array.prototype.slice.call(arguments,0)))}},p=["addEventProperties","addUserProperties","clearEventProperties","identify","resetIdentity","removeEventProperty","setEventProperties","track","unsetEventProperty"],c=0;c<p.length;c++)heap[p[c]]=o(p[c])};
    heap.load(heapProjectId);
    {/literal}
    {if $identifyUserToAnalytics}
    {literal}
    heap.identify(userId);
    heap.addUserProperties({'organisation': organisationId,'is_admin': isAdmin});
    {/literal}
    {/if}
</script>
{/if}