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
    "valueField": "sales",
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
		{if not $borrow@last},{/if}
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
    "fillColors": "#4cbac5",
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
	<h1>Hello there!</h1>
	
	{if $smarty.session.camp['market']}
    <h1 class="light">
        There {if $data['residents']==1} is {else} are {/if} currently 
        <span class="number">{$data['residents']}</span> 
        {if $data['residents']==1} person {else} people {/if} living in 
        <span class="number">{$data['families']}</span> 
        {if $data['families']==1} family {else} families {/if} in {$currentcamp['name']}.
        
        <span class="men">{$data['totalmen']}</span> 
        {if $data['totalmen']==1} is {else} are {/if} male 
        (<span class="men">{$data['menperc']|round}%</span>) and 
        
        <span class="women">{$data['totalwomen']}</span> 
        {if $data['totalwomen']==1} is {else} are {/if} female 
        (<span class="women">{$data['womenperc']|round}%</span>).

        {assign var="child_age" value=$smarty.session.camp['adult_age']-1}
        {if $child_age < 0}
            {assign var="child_age" value=0}
        {/if}

        {if $data['children'] > 0}
            <span class="number">{$data['children']}</span> 
            {if $data['children']==1} person is {else} people are {/if} 
            {$child_age} or younger 
            (<span class="number">{$data['childrenprcnt']|round}%</span>).
        {/if}
        
        <br>
    </h1>
	{/if}
<hr />

{if $smarty.session.camp['market']}
  <h1 class="light">
    All beneficiaries together own 
    <span class="number">{if !empty($data['bank'])}{$data['bank']|number_format:0:",":"."}{else}0{/if}</span> {$currentcamp['currencyname']}.
    
    <span class="number">{if !empty($data['sold'])}{$data['sold']|number_format:0:",":"."}{else}0{/if}</span> items have been sold in the shop in 
    <span class="number">{if !empty($data['marketdays'])}{$data['marketdays']}{else}0{/if}</span> opening days. 
    
    {if !empty($data['popularname']) && $data['popularname'] != 'none'}
      The most popular item is <span class="number">{$data['popularname']}</span>, with 
      <span class="number">{if !empty($data['popularcount'])}{$data['popularcount']|number_format:0:",":"."}{else}0{/if}</span> items sold.
    {/if}
  </h1>
{/if}

{if $smarty.session.camp['bicycle']}<hr />
<hr  /> 
<h1 class="light">{$data['weeklabel']} week {if $data['newcardsM']+$data['newcardsF']}<span class="number">{$data['newcardsM']+$data['newcardsF']}</span>{else}no{/if} new Bicycle Certificates were made{if $data['newcardsM']+$data['newcardsF']}, <span class="men">{$data['newcardsM']}</span> for men and <span class="women">{$data['newcardsF']}</span> for women{/if}. In total <span class="number">{$data['totalcardsM']+$data['totalcardsF']}</span> Bicycle Certificates are active (Of which <span class="women">{$data['totalcardsF']}</span> for women). So <span class="men">{($data['cardsM'])}%</span> of male and <span class="women">{($data['cardsF'])}%</span> of the female beneficiaries have a bicycle card.</h1>
{/if}

{if $smarty.session.camp['workshop']}<hr />
<h1 class="light">{$data['weeklabel']} week {if $data['newbrcardsM']+$data['newbrcardsF']}<span class="number">{$data['newbrcardsM']+$data['newbrcardsF']}</span>{else}no{/if} new Workshop Cards were made{if $data['newbrcardsM']+$data['newbrcardsF']}, <span class="men">{$data['newbrcardsM']}</span> for men and <span class="women">{$data['newbrcardsF']}</span> for women{/if}. In total <span class="number">{$data['totalbrcardsM']+$data['totalcardsF']}</span> Workshop Cards are active (Of which <span class="women">{$data['totalbrcardsF']}</span> for women). So <span class="men">{($data['brcardsM'])}%</span> of men and <span class="women">{($data['brcardsF'])}%</span> of the women beneficiaries have a workshop card.</h1>
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
<hr />
{/if}

{if isset($data['borrow']) && isset($smarty.session.camp['bicycle'])}<h1>Items lent out in the last 21 days</h1>
<div id="chartdiv2"></div>
{/if}

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

