<?php

use OpenCensus\Trace\Tracer;
class Zmarty extends Smarty {
    public function __construct() {
        global $settings, $translate, $lan;

        parent::__construct();
        if (parse_url($_SERVER['HTTP_HOST'], PHP_URL_HOST) != "localhost") {
            $this->debugging = false;
            $this->compile_check = Smarty::COMPILECHECK_OFF;
        }
        $this->setCompileDir($settings['smarty_dir']);
        $this->addTemplateDir($_SERVER['DOCUMENT_ROOT'].$settings['rootdir'].'/templates');
        $this->assign('lan',$lan);
        $this->assign('modal',isset($_GET['modal']));
        $this->assign('settings',$settings);
        $this->assign('translate',$translate);
    }

    public function display($template = NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL) {
        return Tracer::inSpan(
            ['name' => 'smarty:display:'.$template],
            function() use ($template,$cache_id,$compile_id,$parent) {
                return parent::display($template, $cache_id, $compile_id, $parent);
            }
        );
    }
    
    public function fetch($template = NULL, $cache_id = NULL, $compile_id = NULL, $parent = NULL) {
        return Tracer::inSpan(
            ['name' => 'smarty:fetch:'.$template],
            function() use ($template,$cache_id,$compile_id,$parent) {
                return parent::fetch($template, $cache_id, $compile_id, $parent);
            }
        );
    }
}
