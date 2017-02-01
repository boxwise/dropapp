<?php /* Smarty version Smarty-3.1.18, created on 2016-12-21 14:18:37
         compiled from "/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/start.tpl" */ ?>
<?php /*%%SmartyHeaderCode:185441528585a812d2a5e59-57404825%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c9d76978b446277a56d6059e91f319f41f8b1b53' => 
    array (
      0 => '/home/deb5672n12/domains/maartenhunink.com/public_html/drops/flip/templates/start.tpl',
      1 => 1482265496,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '185441528585a812d2a5e59-57404825',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'date' => 0,
    'sales' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_585a812d447a96_48583510',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_585a812d447a96_48583510')) {function content_585a812d447a96_48583510($_smarty_tpl) {?><!-- Styles -->
<style>
#chartdiv {
	width: 45%;
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
	<?php  $_smarty_tpl->tpl_vars['sales'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sales']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value['sales']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['sales']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['sales']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['sales']->key => $_smarty_tpl->tpl_vars['sales']->value) {
$_smarty_tpl->tpl_vars['sales']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['sales']->key;
 $_smarty_tpl->tpl_vars['sales']->iteration++;
 $_smarty_tpl->tpl_vars['sales']->last = $_smarty_tpl->tpl_vars['sales']->iteration === $_smarty_tpl->tpl_vars['sales']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['sales']['last'] = $_smarty_tpl->tpl_vars['sales']->last;
?>
		{
        "date": "<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
",
        "sales": "<?php echo $_smarty_tpl->tpl_vars['sales']->value;?>
",
		}
		<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['sales']['last']) {?>,<?php }?>
	<?php } ?>
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
	
	<h1 class="light">There are currently <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['residents'];?>
</span> people living in Nea Kavala.<br /><span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['families'];?>
</span> families are living in <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['containers'];?>
</span> containers.<br /><span class="men"><?php echo ($_smarty_tpl->tpl_vars['data']->value['totalmen']);?>
</span> of them are male (<span class="men"><?php echo round($_smarty_tpl->tpl_vars['data']->value['menperc']);?>
%</span>) and <span class="women"><?php echo $_smarty_tpl->tpl_vars['data']->value['totalwomen'];?>
</span> are female (<span class="women"><?php echo round($_smarty_tpl->tpl_vars['data']->value['womenperc']);?>
</span>%).<br />Of these people, <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['children'];?>
</span> are <?php echo $_smarty_tpl->tpl_vars['settings']->value['adult-age']-1;?>
 or younger (<span class="number"><?php echo round(($_smarty_tpl->tpl_vars['data']->value['children']/$_smarty_tpl->tpl_vars['data']->value['residents']*100));?>
%</span>).<br /><span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['under18'];?>
</span> are under 18 (<span class="number"><?php echo round(($_smarty_tpl->tpl_vars['data']->value['under18']/$_smarty_tpl->tpl_vars['data']->value['residents']*100));?>
%</span>).</h1>

<hr />
<h2>Sales in the last 14 days</h2>
<div id="chartdiv"></div>

	
	<aside id="aside-container">
		<div class="affix aside-content">
		<div class="tipofday">
			<h3>💡 Tip of the day: <?php echo $_smarty_tpl->tpl_vars['data']->value['title'];?>
</h3>
			<p><?php echo $_smarty_tpl->tpl_vars['data']->value['content'];?>
 <br></br></p>
			<p>Do you have any questions? Check out the manual <a target="_blank" href="https://docs.google.com/document/d/1Cr5Fl5xHYVcMmtN0Z3Ld2TqEaxaRlXWH_jz5Enj6XsY/	edit#heading=h.b2dhx9rzowft">here</a>.</p>
		</div>
		</div>
	</aside>
</div>

<?php }} ?>
