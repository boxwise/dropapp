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
var chart = AmCharts.makeChart( "chartdiv2", {
  "type": "serial",
  "theme": "light",
  "fontFamily": "Helvetica Neue ,Helvetica,Arial,sans-serif",
  "dataProvider": [

	{foreach $data['borrow'] as $date=>$borrow name=borrow}
		{
        "date": "{$date}",
			{foreach $borrow as $key=>$value name=groups}
		        "{$key}": {$value['count']},
			{/foreach}
		}
		{if not $smarty.foreach.days.last},{/if}
	{/foreach}



	],
  "valueAxes": [ {
    "gridColor": "#999999",
	"minimum": 0,
    "gridAlpha": 0.2,
    "dashLength": 0,
        "stackType": "regular",
  } ],
  "gridAboveGraphs": true,
  "startDuration": 1,
  "graphs": [ {
    "balloonText": "<b>[[value]]</b> Bicycles Male", 
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "fillColors": "#34aad1",
    "valueField": "Bicycles M"
  },{
    "balloonText": "<b>[[value]]</b> Bicycles Female", 
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "fillColors": "#db57bd",
    "valueField": "Bicycles F"
  },{
    "balloonText": "<b>[[value]]</b> Gym gear items Male",
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "Gym gear M"
  },{
    "balloonText": "<b>[[value]]</b> Gym gear items Female",
    "fillAlphas": 0.8,
    "lineAlpha": 0.2,
    "type": "column",
    "valueField": "Gym gear F"
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
	<a href="https://boxwise.co" target="_target"><img src="/assets/img/boxwise.jpg" style="max-width:70%;"/></a>

	<h1>Hello there!</h1>
	
	<h1 class="light">
{if $smarty.session.camp['id']==1}
	There are currently <span class="number">{$data['residentscamp']}</span> people living in and around {$currentcamp['name']}, <span class="number">{$data['families']}</span> families are living in <span class="number">{$data['containers']}</span> addresses. <span class="number">{$data['notregistered']}</span> people live in camp unregistered. <span class="number">{$data['residentsoutside']}</span> people live outside, in <span class="number">{$data['containersoutside']}</span> locations.<br />
{else}
	There are currently <span class="number">{$data['residents']}</span> people living in {$currentcamp['name']}, in <span class="number">{$data['families']}</span> families are living in <span class="number">{$data['containers']}</span> addresses.<br />
{/if}
	{$data['weeklabel']} week {if $data['newpeople']}<span class="number">{$data['newpeople']}</span>{else}no{/if} new residents of the camp have been registered by us.<br />
	<span class="men">{($data['totalmen'])}</span> of our community are male (<span class="men">{$data['menperc']|round}%</span>) and <span class="women">{$data['totalwomen']}</span> are female (<span class="women">{$data['womenperc']|round}</span>%).<br />Of these people, <span class="number">{$data['children']}</span> are {$_SESSION['camp']['adult_age']-1} or younger (<span class="number">{($data['children']/$data['residents']*100)|round}%</span>).<br /><span class="number">{$data['under18']}</span> are under 18 (<span class="number">{($data['under18']/$data['residents']*100)|round}%</span>).</h1>
<hr />
<h1 class="light">We have <span class="number">{$data['items']|number_format:0:",":"."}</span> items in our warehouses, in <span class="number">{$data['boxes']|number_format:0:",":"."}</span> boxes. Already <span class="number">{$data['sold']|number_format:0:",":"."}</span> items have been sold in the market in <span class="number">{$data['marketdays']}</span> opening days. Most popular item is <span class="number">{$data['popularname']}</span> with <span class="number">{$data['popularcount']|number_format:0:",":"."}</span> items sold. All people together own <span class="number">{$data['bank']|number_format:0:",":"."}</span> drops.</h1>
{if $smarty.session.camp['bicycle']}<hr />
<h1 class="light">{$data['weeklabel']} week {if $data['newcardsM']+$data['newcardsF']}<span class="number">{$data['newcardsM']+$data['newcardsF']}</span>{else}no{/if} new Bicycle Certificates were made{if $data['newcardsM']+$data['newcardsF']}, <span class="men">{$data['newcardsM']}</span> for men and <span class="women">{$data['newcardsF']}</span> for women{/if}. In total <span class="number">{$data['totalcardsM']+$data['totalcardsF']}</span> Bicycle Certificates are active (Of which <span class="women">{$data['totalcardsF']}</span> for women). So <span class="men">{($data['cardsM'])}%</span> of men and <span class="women">{($data['cardsF'])}%</span> of the women residents have a bicycle card.</h1>
{/if}

{if $smarty.session.camp['workshop']}<hr />
<h1 class="light">{$data['weeklabel']} week {if $data['newbrcardsM']+$data['newbrcardsF']}<span class="number">{$data['newbrcardsM']+$data['newbrcardsF']}</span>{else}no{/if} new Workshop Cards were made{if $data['newbrcardsM']+$data['newbrcardsF']}, <span class="men">{$data['newbrcardsM']}</span> for men and <span class="women">{$data['newbrcardsF']}</span> for women{/if}. In total <span class="number">{$data['totalbrcardsM']+$data['totalcardsF']}</span> Workshop Cards are active (Of which <span class="women">{$data['totalbrcardsF']}</span> for women). So <span class="men">{($data['brcardsM'])}%</span> of men and <span class="women">{($data['brcardsF'])}%</span> of the women residents have a workshop card.</h1>
{/if}

{if $smarty.session.camp['laundry']}<hr />
<h1 class="light">The current laundry cycle (of two weeks) started <span class="number">{$_SESSION['camp']['laundry_cyclestart']|date_format:"%d-%m-%Y"}</span>. Until now <span class="number">{$data['laundry_appointments']}</span> appointments have been made, using <span class="number">{($data['laundry_appointments']/$data['laundry_slots']*100)|number_format:0:",":"."}%</span> of the capacity. We have <span class="number">{$data['laundry_noshow']}</span> no-shows (<span class="number">{($data['laundry_noshow']/$data['laundry_appointments']*100)|number_format:0:",":"."}%</span>). We have served <span class="number">{$data['laundry_beneficiaries']|intval}</span> beneficiaries.</h1>

<h1 class="light">The last completed laundry cycle contained <span class="number">{$data['laundry_prev_appointments']}</span> appointments, using <span class="number">{($data['laundry_prev_appointments']/$data['laundry_slots']*100)|number_format:0:",":"."}%</span> of the capacity. We had <span class="number">{$data['laundry_prev_noshow']}</span> no-shows (<span class="number">{($data['laundry_prev_noshow']/$data['laundry_prev_appointments']*100)|number_format:0:",":"."}%</span>). We did serve <span class="number">{$data['laundry_prev_beneficiaries']|intval}</span> beneficiaries.</h1>
{/if}

<hr />
{if $data['sales']}<h1>Sales in the last 21 days</h1>
<div id="chartdiv"></div>
{/if}
<hr />
{if $data['borrow'] && $smarty.session.camp['bicycle']}<h1>Items lent out in the last 21 days</h1>
<div id="chartdiv2"></div>
{/if}

	
	<aside id="aside-container" class="noprint">
		<div class="affix aside-content">
		<div class="tipofday">
			<h3>💡 Tip of the day: {$data['tip']['title']}</h3>
			<p>{$data['tip']['content']}</p>
		</div>
		</div>
	</aside>
</div>

