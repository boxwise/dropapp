<?php

use OpenCensus\Trace\Tracer;
use Smarty\Smarty;

class Zmarty extends Smarty
{
    /**
     * @var bool
     */
    public $escape_html_default;

    public function __construct()
    {
        global $settings, $translate, $lan;

        parent::__construct();

        // Handle CLI environment
        if ('cli' === php_sapi_name() || !isset($_SERVER['HTTP_HOST'])) {
            $this->debugging = false;
            $this->compile_check = Smarty::COMPILECHECK_OFF;
        } else {
            if ('localhost' != parse_url('http://'.$_SERVER['HTTP_HOST'], PHP_URL_HOST)) {
                $this->debugging = false;
                $this->compile_check = Smarty::COMPILECHECK_OFF;
            }
        }

        if (isset($_GET['smartydebug'])) {
            $this->debugging = true;
        }

        // Updated for Smarty 5.x compatibility
        $this->escape_html_default = true; // Changed from escape_html
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

    // Register custom modifiers - Updated for Smarty 5.x
    private function registerCustomModifiers()
    {
        // Smarty 5.x still supports registerPlugin for modifiers, but with updated syntax
        $this->registerPlugin('modifier', 'is_array', 'is_array_modifier');
        $this->registerPlugin('modifier', 'array_slice', 'array_slice_modifier');
        $this->registerPlugin('modifier', 'implode', 'implode_modifier');
        $this->registerPlugin('modifier', 'intval', 'intval_modifier');
        $this->registerPlugin('modifier', 'explode', 'explode_modifier');
        $this->registerPlugin('modifier', 'count', 'count_modifier');
        $this->registerPlugin('modifier', 'round', 'round_modifier');
        $this->registerPlugin('modifier', 'trim', 'trim_modifier');
        $this->registerPlugin('modifier', 'substr', 'substr_modifier');
        $this->registerPlugin('modifier', 'number_format', 'number_format_modifier');
        $this->registerPlugin('modifier', 'abs', 'abs_modifier');

        // Register alternative names to bypass Smarty 5 DefaultExtension deprecation warnings
        $this->registerPlugin('modifier', 'split_string', 'explode_modifier');
        $this->registerPlugin('modifier', 'join_array', 'implode_modifier');
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

function implode_modifier($value, $sep = '-')
{
    if (!is_array($value)) {
        return $value;
    }

    return implode($sep, $value);
}

function explode_modifier($value, $glue = '')
{
    if (!is_string($value)) {
        return $value;
    }

    return explode($glue, $value);
}

function intval_modifier($value)
{
    return intval($value);
}

function count_modifier($value)
{
    return count($value);
}

function round_modifier($value)
{
    return round($value);
}

function trim_modifier($value)
{
    return trim($value);
}

function substr_modifier($value, $start, $length = null)
{
    return substr($value, $start, $length);
}

function number_format_modifier($value, $decimals, $dec_point, $thousands_sep)
{
    return number_format($value, $decimals, $dec_point, $thousands_sep);
}

function abs_modifier($value)
{
    return abs($value);
}
