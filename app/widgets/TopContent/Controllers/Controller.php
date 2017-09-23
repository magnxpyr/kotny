<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
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
    /**
     * @param array $categories
     * @param int $limit
     */
    public function indexAction($categories = null, $limit = 10)
    {
        $model = $this->modelsManager->createBuilder()
            ->columns('content.*, category.*, user.*')
            ->addFrom('Module\Core\Models\Content', 'content')
            ->addFrom('Module\Core\Models\Category', 'category')
            ->addFrom('Module\Core\Models\User', 'user')
            ->andWhere('content.category = category.id')
            ->andWhere('content.created_by = user.id')
            ->orderBy('content.created_at DESC')
            ->limit($limit);
        if ($categories) {
            $model->andWhere('category.alias in (:category:)', ['category' => implode(",", $categories)]);
        }
        
        $this->viewWidget->setVar('model', $model->getQuery()->execute());
    }
}