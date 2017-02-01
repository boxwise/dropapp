<?php /* Smarty version Smarty-3.1.18, created on 2016-03-15 15:44:49
         compiled from "./templates/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2036977585556dab44372dc8-62665939%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '97c13ae6868bbc459509c9f1b968154acd23eecc' => 
    array (
      0 => './templates/header.tpl',
      1 => 1458053087,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2036977585556dab44372dc8-62665939',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.18',
  'unifunc' => 'content_556dab443dc374_11923830',
  'variables' => 
  array (
    'searchtermbox' => 0,
    'translate' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556dab443dc374_11923830')) {function content_556dab443dc374_11923830($_smarty_tpl) {?>
	<div class="st-search st-effect">

		<div class="container ">
			<div class="row">
				<div class="col col-sm-12">
					<div class="search-container">
						<div class="search-form">
							<div class="search-close-header"><a class="btn btn-small btn-blue btn-inverted search-close-btn">Zoeken sluiten <span class="icon-close"></span></a></div>
							<form autocomplete="off">
								<div class="form-row clearfix">
									<input type="text" name="searchterm" id="searchterm" autocorrect="off" automplete="off" autocapitalize="off" spellcheck="false" class="search-input form-adjust" value="<?php echo $_smarty_tpl->tpl_vars['searchtermbox']->value;?>
" placeholder="<?php echo $_smarty_tpl->tpl_vars['translate']->value['search_placeholder'];?>
"/>
									<button type="submit" class="btn-submit-search"><span class="icon icon-search"></span></button>
								</div>
							</form>
						</div>
						<div id="search-results"></div>
					</div>
				</div>
			</div>
		</div>

	</div>
	<nav role="navigation" aria-label="main" class="nav-main st-menu st-effect">
		<div class="nav-main-header">
			<div class="nav-main-close"><a href="#" class="icon-close"></a></div>
		</div>
		<ul>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='') {?> class="active"<?php }?>><a href="/">Home</a></li>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='ondernemen') {?> class="active"<?php }?>><a href="/ondernemen">Ondernemen</a></li>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='financieren') {?> class="active"<?php }?>><a href="/financieren">Financieren</a></li>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='markt-ontwikkelen') {?> class="active"<?php }?>><a href="/markt-ontwikkelen">Markt Ontwikkelen</a></li>
			<li>&nbsp;</li>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='productoverzicht') {?> class="active"<?php }?>><a href="/productoverzicht">Alle producten</a></li>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='agenda') {?> class="active"<?php }?>><a href="/agenda">Agenda</a></li>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='over-ons') {?> class="active"<?php }?>><a href="/over-ons">Over ons</a></li>
			<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='medewerkers') {?> class="active"<?php }?>><a href="/medewerkers">Medewerkers</a></li>
		</ul>
	</nav>
	<div class="st-pusher">
		<div class="st-content">
			<div class="st-content-inner clearfix">
				<div class="container triangleshape-container">
					<div class="header-triangleshape"><img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0nMTAwMHB4JyBoZWlnaHQ9JzEwMDBweCcgdmlld0JveD0nMCAwIDEwMDAgMTAwMCcgeG1sbnM9J2h0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnJz48cGF0aCBjbGFzcz0ndHJpYW5nbGVzaGFwZScgc3R5bGU9J2ZpbGw6cmdiYSg0NiwxODIsMjQ4LDAuMyk7JyBkPSdNMCwwIEwxMDAwLDAgTDAsMTAwMCBMMCwwIFonPjwvcGF0aD48L3N2Zz4=" alt="Cultuur+Ondernemen" /></div>
				</div>
				<header class="header-main">
					<div class="header-fixed-wrapper">
						<div class="container">
							<div class="nav-meta clearfix">
								<div class="nav-main-open nav-main-open-meta"><a href="#" class="icon-menu" aria-label="navigation menu"></a></div>
								<nav role="navigation" aria-label="meta">
									<ul>
										<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='') {?> class="active"<?php }?>><a href="/">home</a></li>

										<li<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='contact') {?> class="active"<?php }?>><a href="/contact">contact</a></li>
										<li class="meta-search"><a class="icon-btn icon-search search-open" href="/zoeken"></a></li>
									</ul>
								</nav>
							</div>
							<div class="nav-menu-logo">
								<div class="nav-main-open"><a href="" class="icon-menu"></a></div>
								<div class="logo"><a href="/" class="logo-link">Cultuur+Ondernemen</a></div>
							</div>
						</div>
					</div>
					<div class="container intro-container">
						<h1 class="intro"><?php echo $_smarty_tpl->tpl_vars['translate']->value['boilerplate'];?>
</h1>
						
						<?php if (ltrim($_SERVER['REQUEST_URI'],'/')=='') {?> 
						<div class="row intro-blocks">
							<div class="col col-sm-6">
								<a href="http://www.fondscultuurfinanciering.nl" target="_blank">
									<img src="/site/img/banner-financiering.png" class="banner-financiering" alt="" />
								</a>	
							</div>
							<div class="col col-sm-6">
							  <a href="http://www.governancecodecultuur.nl" target="_blank">
									<img src="/site/img/banner-governance.png" class="banner-governance" alt="" />
								</a>	
							</div>
						</div>
						<?php }?>

					</div>
				</header><?php }} ?>
