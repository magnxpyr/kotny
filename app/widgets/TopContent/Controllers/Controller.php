<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\TopContent\Controllers;

/**
 * Class Controller
 * @package Widget\TopContent\Controllers
 */
class Controller extends \Engine\Widget\Controller
{
    public function indexAction()
    {
        $model = $this->modelsManager->createBuilder()
            ->columns('content.*, category.*, user.*')
            ->addFrom('Module\Core\Models\Content', 'content')
            ->addFrom('Module\Core\Models\Category', 'category')
            ->addFrom('Module\Core\Models\User', 'user')
            ->andWhere('content.category = category.id')
            ->andWhere('content.created_by = user.id')
            ->orderBy('content.created_at DESC')
            ->limit($this->getParam("_limit"));
        if ($this->getParam("_category")) {
            $model->andWhere('category.alias = :category:', ['category' => $this->getParam("_category")]);
        }
        
        $this->viewWidget->setVar('model', $model->getQuery()->execute());
    }
}