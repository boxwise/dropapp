<!-- Styles -->
<style>
#chartdiv {
	height: 300px;
}
</style>

<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>

<!-- Chart code -->
<script>
var chart = AmCharts.makeChart( "chartdiv", {
  "type": "serial",
  "theme": "light",
  "fontFamily": "Helvetica Neue ,Helvetica,Arial,sans-serif",
  "dataProvider": [
	{foreach $data['sales'] as $date=>$sales name=sales}
		{
        "date": "{$date}",
        "sales": "{$sales}",
		}
		{if not $smarty.foreach.sales.last},{/if}
	{/foreach}
	],
  "valueAxes": [ {
    "gridColor": "#FFFFFF",
    "gridAlpha": 0.2,
    "dashLength": 0
  } ],
  "gridAboveGraphs": true,
  "startDuration": 1,
  "graphs": [ {
    "balloonText": "[[category]]: <b>[[value]]</b>",
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "sales"
  } ],
  "chartCursor": {
    "categoryBalloonEnabled": false,
    "cursorAlpha": 0,
    "zoomable": false
  },
  "categoryField": "date",
  "categoryAxis": {
    "gridPosition": "start",
    "gridAlpha": 0,
    "tickPosition": "start",
    "tickLength": 5
  },
  "export": {
    "enabled": false
  }

} );
</script>

<!-- HTML -->
<div class="content-form">
	<h1>Hello there!</h1>
	
	<h1 class="light">There are currently <span class="number">{$data['residents']}</span> people living in {$currentcamp['name']}.<br /><span class="number">{$data['families']}</span> families are living in <span class="number">{$data['containers']}</span> containers.<br /><span class="men">{($data['totalmen'])}</span> of them are male (<span class="men">{$data['menperc']|round}%</span>) and <span class="women">{$data['totalwomen']}</span> are female (<span class="women">{$data['womenperc']|round}</span>%).<br />Of these people, <span class="number">{$data['children']}</span> are {$settings['adult-age']-1} or younger (<span class="number">{($data['children']/$data['residents']*100)|round}%</span>).<br /><span class="number">{$data['under18']}</span> are under 18 (<span class="number">{($data['under18']/$data['residents']*100)|round}%</span>).</h1>
<hr />
<h1 class="light">We have <span class="number">{$data['items']}</span> items in our warehouses, in <span class="number">{$data['boxes']}</span> boxes. Already <span class="number">{$data['sold']}</span> items have been sold in the market in <span class="number">{$data['marketdays']}</span> opening days. Most popular item is <span class="number">{$data['popularname']}</span> with <span class="number">{$data['popularcount']}</span> items sold.</h1>
<hr />
<h2>Sales in the last 14 days</h2>
<div id="chartdiv"></div>

	
	<aside id="aside-container">
		<div class="affix aside-content">
		<div class="tipofday">
			<h3>💡 Tip of the day: {$data['tip']['title']}</h3>
			<p>{$data['tip']['content']} <br></br></p>
			<p>Do you have any questions? Check out the manual <a target="_blank" href="https://docs.google.com/document/d/1Cr5Fl5xHYVcMmtN0Z3Ld2TqEaxaRlXWH_jz5Enj6XsY/	edit#heading=h.b2dhx9rzowft">here</a>.</p>
		</div>
		</div>
	</aside>
</div>

