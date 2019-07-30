<?php

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
}
