<?
class Zmarty extends Smarty {
	public function __construct() {
		global $settings, $translate, $lan, $flipdir;
		
		parent::__construct();
		$this->use_include_path = true; 
		$this->debugging = false;
		$this->caching = false;
		$this->cache_lifetime = 120;
		$this->compile_dir = $_SERVER['DOCUMENT_ROOT'].$flipdir.'/templates_c';
		$this->addTemplateDir($_SERVER['DOCUMENT_ROOT'].$flipdir.'/templates');
		$this->addTemplateDir($_SERVER['DOCUMENT_ROOT'].'/site/templates');
		$this->addTemplateDir($settings['globaldir'].'/templates');
		$this->assign('lan',$lan);
		$this->assign('modal',isset($_GET['modal']));
		$this->assign('flipdir',$flipdir);
		$this->assign('settings',$settings);
		$this->assign('translate',$translate);
		if(!$_SERVER['Local']) $this->registerFilter("output", "minify_html");	
	}
	
}

function minify_html($tpl_output, Smarty_Internal_Template $template) {
    $tpl_output = preg_replace('![\t ]*[\r\n]+[\t ]*!', ' ', $tpl_output);
    return $tpl_output;
}

// register the outputfilter
