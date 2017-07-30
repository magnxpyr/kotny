<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Widget\Content;

/**
 * Class Controller
 * @package Widget
 */
class Controller extends \Engine\Widget\Controller
{
    public function indexAction($category = null, $limit = 10)
    {
        $model = $this->modelsManager->createBuilder()
            ->columns('c.*, cat.*, u.*')
            ->addFrom('Module\Core\Models\Content', 'c')
            ->addFrom('Module\Core\Models\Category', 'cat')
            ->addFrom('Module\Core\Models\User', 'u')
            ->andWhere('c.category = cat.id')
            ->andWhere('c.created_by = u.id')
            ->orderBy('c.created_at DESC')
            ->limit($limit);
        if ($category) {
            $model->andWhere('cat.alias = :category:', ['category' => $category]);
        }
        
        $this->viewWidget->setVar('model', $model->getQuery()->execute());
    }
}