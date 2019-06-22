<!-- Global site tag (gtag.js) - Google Analytics -->

{if $smarty.server.HTTP_HOST == 'staging.boxwise.co'}
    {literal}
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135092361-3"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-135092361-3');
        </script>
    {/literal}
{elseif $smarty.server.HTTP_HOST == 'demo.boxwise.co'}
    {literal}
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135092361-4"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-135092361-4');
        </script>
    {/literal}
{elseif $smarty.server.HTTP_HOST == 'app.boxwise.co'}
    {literal}   
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-135092361-2"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', 'UA-135092361-2');
        </script>
    {/literal}
{/if}