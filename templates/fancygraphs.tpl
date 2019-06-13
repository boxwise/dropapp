<!-- Styles -->

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />

<!-- Chart code -->
<script>
var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
    "theme": "light",
    "fontFamily": "Helvetica Neue ,Helvetica,Arial,sans-serif",
    "rotate": true,
    "legend": {
        "useGraphSettings": true
    },
    "dataProvider": [
	{for $var=0 to $data['oldest']}
	{assign var="age" value=$data['oldest']-$var}
		{
	        "age": "{$age}",
	        "men": -{$data['men'][$age]|intval},
	        "women": {$data['women'][$age]|intval}
	    }{if $age>0},{/if}
    {/for}
    
    ],
    "valueAxes": [{
        "axisAlpha": 0,
        "position": "left",
    }],
    "startDuration": 0.5,
    "graphs": [{
        "fillColors": "#34aad1",
        "lineColor": "#34aad1",
	    "fillAlphas": 1,
	    "lineAlpha": 0,
	    "type": "column",
	    "valueField": "men",
	    "title": "Men",
	    "clustered": false,
	    "labelFunction": function(item) {
	      return Math.abs(item.values.value);
	    },
	    "balloonFunction": function(item) {
	      return Math.abs(item.values.value) + " men of " + item.category + " year old";
	    }

    }, {
        "fillColors": "#db57bd",
        "lineColor": "#db57bd",
	    "fillAlphas": 1,
	    "lineAlpha": 0,
	    "type": "column",
	    "valueField": "women",
	    "title": "Women",
	    "clustered": false,
	    "labelFunction": function(item) {
	      return Math.abs(item.values.value);
	    },
	    "balloonFunction": function(item) {
	      return Math.abs(item.values.value) + " women of " + item.category + " year old";
	    }
    }],
    "chartCursor": {
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "age",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "fillAlpha": 0.05,
        "fillColor": "#000000",
        "gridAlpha": 0,
        "position": "bottom"
    },
    "export": {
    	"enabled": true,
        "position": "bottom-right"
     }
});

var chart2 = AmCharts.makeChart("chartdiv2", {
    "type": "serial",
    "theme": "light",
    "fontFamily": "Helvetica Neue ,Helvetica,Arial,sans-serif",
    "legend": {
        "useGraphSettings": true
    },
    "dataProvider": [
	{foreach $data['familysize'] as $key=>$d name="size"}
		{
	        "size": "{$key}",
	        "male": {$d['male']},
	        "female": {$d['female']},
	        "boys": {$d['boys']},
	        "girls": {$d['girls']},
	    } {if not $smarty.foreach.size.last},{/if}
    {/foreach}
    
    ],
    "valueAxes": [{
        "stackType": "regular",
        "axisAlpha": 0,
        "position": "left",
    }],
    "startDuration": 0.5,
    "graphs": [{
       "balloonText": "[[value]] men in families of [[category]] persons",
        "fillColors": "#34aad1",
        "lineColor": "#34aad1",
        "title": "Number of men per family size",
        "type": "column",
        "valueField": "male",
		"fillAlphas": 0.65,
		"lineAlphas": 0
    },{
        "balloonText": "[[value]] boys in families of [[category]] persons",
        "fillColors": "#8ac9de",
        "lineColor": "#8ac9de",
        "title": "Number of boys per family size",
        "type": "column",
        "valueField": "boys",
		"fillAlphas": 0.85,
		"lineAlphas": 0
    },{
        "balloonText": "[[value]] women in families of [[category]] persons",
        "fillColors": "#db57bd",
        "lineColor": "#db57bd",
        "title": "Number of women per family size",
        "type": "column",
        "valueField": "female",
		"fillAlphas": 0.85,
		"lineAlphas": 0
     },{
       "balloonText": "[[value]] girls in families of [[category]] persons",
        "fillColors": "#eaa3da",
        "lineColor": "#eaa3da",
        "title": "Number of girls per family size",
        "type": "column",
        "valueField": "girls",
		"fillAlphas": 0.85,
		"lineAlphas": 0
    }],
    "depth3D": 20,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "chartCursor": {
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "size",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0,
        "fillAlpha": 0.05,
        "fillColor": "#000000",
        "gridAlpha": 0,
        "position": "bottom"
    },
    "export": {
    	"enabled": true,
        "position": "bottom-right"
     }
});
</script>

<!-- HTML -->
<div id="chartdiv" class="chartdiv"></div>	
<div id="chartdiv2" class="chartdiv"></div>	
<div class="fc"></div>
<h1 class="light">
{if $smarty.session.camp['id']==1}
	There are currently <span class="number">{$data['residentscamp']}</span> people living in {$currentcamp['name']}, <br /><span class="number">{$data['families']}</span> families are living in <span class="number">{$data['containerscamp']}</span> containers. <span class="number">{$data['notregistered']}</span> people live in camp unregistered.<br /> <span class="number">{$data['residentsoutside']}</span> people live outside, in <span class="number">{$data['containersoutside']}</span> locations.<br />
{else}
	There are currently <span class="number">{$data['residents']}</span> people living in {$currentcamp['name']},<br /> in <span class="number">{$data['families']}</span> families are living in <span class="number">{$data['containers']}</span> containers.<br />
{/if}


<span class="men">{($data['totalmen'])}</span> of them are male (<span class="men">{$data['menperc']|round}%</span>) and <span class="women">{$data['totalwomen']}</span> are female (<span class="women">{$data['womenperc']|round}</span>%).<br />Of these people, <span class="number">{$data['children']}</span> are {$_SESSION['camp']['adult_age']-1} or younger (<span class="number">{($data['children']/$data['residents']*100)|round}%</span>).<br /><span class="number">{$data['under18']}</span> are under 18 (<span class="number">{($data['under18']/$data['residents']*100)|round}%</span>).</h1>
<!-- Styles -->
