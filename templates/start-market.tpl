<!-- Styles -->
<style>
#chartdiv {
	height: 300px;
}
#chartdiv2 {
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
		},
	{/foreach}
	],
  "valueAxes": [ {
    "gridColor": "#999999",
	"minimum": 0,
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
    "fillColors": "#848689",
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
	
	{if $smarty.session.camp['market']}
		<h1 class="light">
			There {if $data['residents']==1} is {else} are {/if} currently <span class="number">{$data['residents']}</span> {if $data['residents']==1} person {else} people {/if} living in <span class="number">{$data['families']}</span> {if $data['families']==1} familiy {else} families {/if} in {$currentcamp['name']}.
      <span class="men">{($data['totalmen'])}</span> {if $data['totalmen']==1} is {else} are {/if} male (<span class="men">{$data['menperc']|round}%</span>) and <span class="women">{$data['totalwomen']}</span> {if $data['totalwomen']==1} is {else} are {/if} female (<span class="women">{$data['womenperc']|round}%</span>).
      <span class="number">{$data['children']}</span> {if $data['children']==1}person is {else} people are {/if} {$smarty.session.camp['adult_age']-1} or younger (<span class="number">{($data['childrenprcnt'])|round}%</span>).
      <br>
    </h1>
    <h1 class = "light">
    <hr />
      {$data['weeklabel']} week, {if $data['newpeople']}<span class="number">{$data['newpeople']}</span>{else}no{/if} new {if $data['newpeople']==1} beneficiary was {else} beneficiaries were {/if} registered in {$currentcamp['name']}.
 			{if isset($data['notregistered'])}
  				Additionally there {if $data['notregistered']==1}is {else} are {/if} <span class="number">{$data['notregistered']}</span> unregistered {if $data['notregistered']==1} person{else} people{/if}.
  			{/if}
		</h1>
	{/if}
<hr />

<h1 class="light">
{if $smarty.session.camp['market']}
  All beneficiaries together own <span class="number">{$data['bank']|number_format:0:",":"."}</span> {$currentcamp['currencyname']}.
	<span class="number">{$data['sold']|number_format:0:",":"."}</span> items have been sold in the shop in <span class="number">{$data['marketdays']}</span> opening days. The most popular item is <span class="number">{$data['popularname']}</span>, with <span class="number">{$data['popularcount']|number_format:0:",":"."}</span> items sold.</h1>
{/if}

{if $smarty.session.camp['laundry']}<hr />
<h1 class="light">The current laundry cycle (of two weeks) started <span class="number">{$_SESSION['camp']['laundry_cyclestart']|date_format:"%d-%m-%Y"}</span>. Until now <span class="number">{$data['laundry_appointments']}</span> appointments have been made, using <span class="number">{($data['laundry_appointments']/$data['laundry_slots']*100)|number_format:0:",":"."}%</span> of the capacity. We have <span class="number">{$data['laundry_noshow']}</span> no-shows (<span class="number">{($data['laundry_noshow']/$data['laundry_appointments']*100)|number_format:0:",":"."}%</span>). We have served <span class="number">{$data['laundry_beneficiaries']|intval}</span> beneficiaries.</h1>

<h1 class="light">The last completed laundry cycle contained <span class="number">{$data['laundry_prev_appointments']}</span> appointments, using <span class="number">{($data['laundry_prev_appointments']/$data['laundry_slots']*100)|number_format:0:",":"."}%</span> of the capacity. We had <span class="number">{$data['laundry_prev_noshow']}</span> no-shows (<span class="number">{($data['laundry_prev_noshow']/$data['laundry_prev_appointments']*100)|number_format:0:",":"."}%</span>). We did serve <span class="number">{$data['laundry_prev_beneficiaries']|intval}</span> beneficiaries.</h1>
{/if}
<h1 class="light">
{if isset($data['warehouse'])}
	We have <span class="number">{$data['items']|number_format:0:",":"."}</span> items in our warehouses, in <span class="number">{$data['boxes']|number_format:0:",":"."}</span> boxes.
{/if}
</h1>

<hr />
{if isset($data['sales'])}<h1>Sales in the last 21 days</h1>
<div id="chartdiv"></div>
{/if}
<hr />

<!-- Disabled Tipofday since the data is outdated
	<aside id="aside-container" class="noprint">
		<div class="affix aside-content">
		<div class="tipofday">
			<h3>💡 Tip of the day: {$data['tip']['title']}</h3>
			<p>{$data['tip']['content'] nofilter}</p>
		</div>
		</div>
	</aside> -->
</div>

