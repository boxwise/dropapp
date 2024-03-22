<?php

use OpenCensus\Trace\Tracer;

class Zmarty extends Smarty
{
    public function __construct()
    {
        global $settings, $translate, $lan;

        parent::__construct();
        if ('localhost' != parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST)) {
            $this->debugging = false;
            $this->compile_check = Smarty::COMPILECHECK_OFF;
        }
        if (isset($_GET['smartydebug'])) {
            $this->debugging = true;
        }

        $this->escape_html = true;
        $this->merge_compiled_includes = true;
        $this->setCompileDir(__DIR__.'/../../templates/templates_c');
        $this->setTemplateDir(__DIR__.'/../../templates');
        $this->assign('lan', $lan);
        $this->assign('modal', isset($_GET['modal']));
        $this->assign('translate', $translate);

        // Register custom modifiers
        $this->registerCustomModifiers();
    }

    public function display($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return Tracer::inSpan(
            ['name' => 'smarty:display:'.$template],
            fn () => parent::display($template, $cache_id, $compile_id, $parent)
        );
    }

    public function fetch($template = null, $cache_id = null, $compile_id = null, $parent = null)
    {
        return Tracer::inSpan(
            ['name' => 'smarty:fetch:'.$template],
            fn () => parent::fetch($template, $cache_id, $compile_id, $parent)
        );
    }

    // Register custom modifiers
    private function registerCustomModifiers()
    {
        $this->registerPlugin(Smarty::PLUGIN_MODIFIER, 'is_array', 'is_array_modifier');
        $this->registerPlugin(Smarty::PLUGIN_MODIFIER, 'array_slice', 'array_slice_modifier');
        $this->registerPlugin(Smarty::PLUGIN_MODIFIER, 'implode', 'implode_modifier');
        $this->registerPlugin(Smarty::PLUGIN_MODIFIER, 'implode_array', 'implode_array_modifier');
    }
}

function is_array_modifier($value)
{
    return is_array($value);
}

function array_slice_modifier($value, $offset, $length = null, $preserveKeys = false)
{
    if (!is_array($value)) {
        return $value;
    }

    return array_slice($value, $offset, $length, $preserveKeys);
}

function implode_modifier($value, $glue = ', ')
{
    if (!is_array($value)) {
        return $value;
    }

    return implode($glue, $value);
}

function implode_array_modifier($value, $glue = '')
{
    if (!is_array($value)) {
        return $value;
    }

    return implode($glue, $value);
}
