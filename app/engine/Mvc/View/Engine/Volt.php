<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine\Mvc\View\Engine;

use Engine\Assets\Manager;

/**
 * Class Volt
 * @package Engine\Mvc\View\Engine
 */
class Volt extends \Phalcon\Mvc\View\Engine\Volt
{
    public function initCompiler()
    {
        $di = $this->getDI();
        $compiler = $this->getCompiler();

        // add a function
        $compiler->addFunction('f', function ($resolvedArgs, $exprArgs) {
            return 'function($model){ return ' . trim($resolvedArgs, "'\"") . ';}';
        });

        $compiler->addFunction('addViewJs', function ($resolvedArgs, $exprArgs) use ($di) {
            $file = $di->getView()->getBasePath(). $di->getView()->getViewsDir() .
                str_replace("'", "", $resolvedArgs);
            $di->getAssets()->collection(Manager::OUTPUT_VIEW_JS)->addJs($file);
        });

        $compiler->addFunction('addViewCss', function ($resolvedArgs, $exprArgs) use ($di) {
            $file = $di->getView()->getBasePath(). $di->getView()->getViewsDir() .
                str_replace("'", "", $resolvedArgs);
            $di->getAssets()->collection(Manager::OUTPUT_VIEW_CSS)->addJs($file);
        });
    }
}