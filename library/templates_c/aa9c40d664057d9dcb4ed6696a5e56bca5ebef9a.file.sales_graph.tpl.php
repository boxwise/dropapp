<?php /* Smarty version Smarty-3.1.18, created on 2017-02-15 19:19:41
         compiled from "/Users/bart/Websites/themarket/library/templates/sales_graph.tpl" */ ?>
<?php /*%%SmartyHeaderCode:114459805458a48dad096b68-62931860%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'aa9c40d664057d9dcb4ed6696a5e56bca5ebef9a' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/sales_graph.tpl',
      1 => 1486395497,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '114459805458a48dad096b68-62931860',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'date' => 0,
    'day' => 0,
    'key' => 0,
    'value' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a48dad0ebeb7_83997955',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a48dad0ebeb7_83997955')) {function content_58a48dad0ebeb7_83997955($_smarty_tpl) {?><!-- Styles -->
<style>
#chartdiv {
	width: 100%;
	height: 600px;
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
var chart = AmCharts.makeChart("chartdiv", {
    "type": "serial",
	"theme": "light",
    "fontFamily": "Helvetica Neue ,Helvetica,Arial,sans-serif",
    "legend": {
        "horizontalGap": 10,
        "maxColumns": 1,
        "position": "right",
		"useGraphSettings": true,
		"markerSize": 10
    },
    "dataProvider": [
	<?php  $_smarty_tpl->tpl_vars['day'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['day']->_loop = false;
 $_smarty_tpl->tpl_vars['date'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['day']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['day']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['day']->key => $_smarty_tpl->tpl_vars['day']->value) {
$_smarty_tpl->tpl_vars['day']->_loop = true;
 $_smarty_tpl->tpl_vars['date']->value = $_smarty_tpl->tpl_vars['day']->key;
 $_smarty_tpl->tpl_vars['day']->iteration++;
 $_smarty_tpl->tpl_vars['day']->last = $_smarty_tpl->tpl_vars['day']->iteration === $_smarty_tpl->tpl_vars['day']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['days']['last'] = $_smarty_tpl->tpl_vars['day']->last;
?>
		{
        "date": "<?php echo $_smarty_tpl->tpl_vars['date']->value;?>
",
			<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['day']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['value']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['value']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
 $_smarty_tpl->tpl_vars['value']->iteration++;
 $_smarty_tpl->tpl_vars['value']->last = $_smarty_tpl->tpl_vars['value']->iteration === $_smarty_tpl->tpl_vars['value']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['groups']['last'] = $_smarty_tpl->tpl_vars['value']->last;
?>
		        "<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
": <?php echo $_smarty_tpl->tpl_vars['value']->value;?>

				<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['groups']['last']) {?>,<?php }?>
			<?php } ?>
		}
		<?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['days']['last']) {?>,<?php }?>
	<?php } ?>
	],
    "valueAxes": [{
        "stackType": "regular",
        "axisAlpha": 0,
        "position": "left",
    }],
    "graphs": [{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Underwear",
        "type": "column",
		"color": "#000000",
        "valueField": "Underwear"

    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Trousers",
        "type": "column",
		"color": "#000000",
        "valueField": "Trousers"
    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Tops",
        "type": "column",
		"color": "#000000",
        "valueField": "Tops"
    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Accesoiries",
        "type": "column",
		"color": "#000000",
        "valueField": "Accesoiries"
    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Shoes",
        "type": "column",
		"color": "#000000",
        "valueField": "Shoes"
    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Jackets",
        "type": "column",
		"color": "#000000",
        "valueField": "Jackets"
    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Skirts/Dresses",
        "type": "column",
		"color": "#000000",
        "valueField": "Skirts/Dresses"
    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Baby",
        "type": "column",
		"color": "#000000",
        "valueField": "Baby"
    },{
        "balloonText": "<b>[[title]]</b><br><span style='font-size:14px'>[[category]]: <b>[[value]]</b></span>",
        "fillAlphas": 0.8,
        "labelText": "[[value]]",
        "lineAlpha": 0.3,
        "title": "Other",
        "type": "column",
		"color": "#000000",
        "valueField": "Other"
	}],
    "depth3D": 20,
	"angle": 30,
    "chartCursor": {
        "categoryBalloonEnabled": false,
        "cursorAlpha": 0,
        "zoomable": false
    },
    "categoryField": "date",
    "categoryAxis": {
        "gridPosition": "start",
        "axisAlpha": 0.3,
        "gridAlpha": 0,
        "position": "left"
    },
    "export": {
    	"enabled": true
     }

});
</script>

<!-- HTML -->
<div id="chartdiv"></div><?php }} ?>
