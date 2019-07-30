<?php

use OpenCensus\Trace\Tracer;
class Zmarty extends Smarty {
	public function __construct() {
		global $settings, $translate, $lan;

		parent::__construct();
        $this->debugging = false;
        $this->setCompileDir($settings['smarty_dir']);
		$this->addTemplateDir($_SERVER['DOCUMENT_ROOT'].$settings['rootdir'].'/templates');
		$this->assign('lan',$lan);
		$this->assign('modal',isset($_GET['modal']));
		$this->assign('settings',$settings);
		$this->assign('translate',$translate);
    }

    public function fetch($template = NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL) {
        return Tracer::inSpan(
            ['name' => 'fetchTemplate:'.$template],
            function() use ($template,$cache_id,$compile_id,$parent) {
                return parent::fetch($template, $cache_id, $compile_id, $parent);
            }
        );
    }
}
