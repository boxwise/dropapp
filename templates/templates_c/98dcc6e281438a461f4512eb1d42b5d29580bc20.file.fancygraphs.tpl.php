<?php /* Smarty version Smarty-3.1.18, created on 2017-02-19 12:18:31
         compiled from "/Users/bart/Websites/themarket/library/templates/fancygraphs.tpl" */ ?>
<?php /*%%SmartyHeaderCode:59676403258a970f7af6c38-01257942%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '98dcc6e281438a461f4512eb1d42b5d29580bc20' => 
    array (
      0 => '/Users/bart/Websites/themarket/library/templates/fancygraphs.tpl',
      1 => 1486395493,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '59676403258a970f7af6c38-01257942',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'data' => 0,
    'var' => 0,
    'age' => 0,
    'key' => 0,
    'd' => 0,
    'settings' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_58a970f7b98429_92608942',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_58a970f7b98429_92608942')) {function content_58a970f7b98429_92608942($_smarty_tpl) {?><!-- Styles -->

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
	<?php $_smarty_tpl->tpl_vars['var'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['var']->step = 1;$_smarty_tpl->tpl_vars['var']->total = (int) ceil(($_smarty_tpl->tpl_vars['var']->step > 0 ? $_smarty_tpl->tpl_vars['data']->value['oldest']+1 - (0) : 0-($_smarty_tpl->tpl_vars['data']->value['oldest'])+1)/abs($_smarty_tpl->tpl_vars['var']->step));
if ($_smarty_tpl->tpl_vars['var']->total > 0) {
for ($_smarty_tpl->tpl_vars['var']->value = 0, $_smarty_tpl->tpl_vars['var']->iteration = 1;$_smarty_tpl->tpl_vars['var']->iteration <= $_smarty_tpl->tpl_vars['var']->total;$_smarty_tpl->tpl_vars['var']->value += $_smarty_tpl->tpl_vars['var']->step, $_smarty_tpl->tpl_vars['var']->iteration++) {
$_smarty_tpl->tpl_vars['var']->first = $_smarty_tpl->tpl_vars['var']->iteration == 1;$_smarty_tpl->tpl_vars['var']->last = $_smarty_tpl->tpl_vars['var']->iteration == $_smarty_tpl->tpl_vars['var']->total;?>
	<?php $_smarty_tpl->tpl_vars["age"] = new Smarty_variable($_smarty_tpl->tpl_vars['data']->value['oldest']-$_smarty_tpl->tpl_vars['var']->value, null, 0);?>
		{
	        "age": "<?php echo $_smarty_tpl->tpl_vars['age']->value;?>
",
	        "men": -<?php echo intval($_smarty_tpl->tpl_vars['data']->value['men'][$_smarty_tpl->tpl_vars['age']->value]);?>
,
	        "women": <?php echo intval($_smarty_tpl->tpl_vars['data']->value['women'][$_smarty_tpl->tpl_vars['age']->value]);?>

	    }<?php if ($_smarty_tpl->tpl_vars['age']->value>0) {?>,<?php }?>
    <?php }} ?>
    
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
	<?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['data']->value['familysize']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['d']->total= $_smarty_tpl->_count($_from);
 $_smarty_tpl->tpl_vars['d']->iteration=0;
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['d']->key;
 $_smarty_tpl->tpl_vars['d']->iteration++;
 $_smarty_tpl->tpl_vars['d']->last = $_smarty_tpl->tpl_vars['d']->iteration === $_smarty_tpl->tpl_vars['d']->total;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']["size"]['last'] = $_smarty_tpl->tpl_vars['d']->last;
?>
		{
	        "size": "<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
",
	        "male": <?php echo $_smarty_tpl->tpl_vars['d']->value['male'];?>
,
	        "female": <?php echo $_smarty_tpl->tpl_vars['d']->value['female'];?>
,
	        "boys": <?php echo $_smarty_tpl->tpl_vars['d']->value['boys'];?>
,
	        "girls": <?php echo $_smarty_tpl->tpl_vars['d']->value['girls'];?>
,
	    } <?php if (!$_smarty_tpl->getVariable('smarty')->value['foreach']['size']['last']) {?>,<?php }?>
    <?php } ?>
    
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
<h1 class="light">There are <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['residents'];?>
</span> people living in Nea Kavala.<br /><span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['families'];?>
</span> Families are living in <span class="number"><?php echo $_smarty_tpl->tpl_vars['data']->value['containers'];?>
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
<!-- Styles -->
<?php }} ?>
