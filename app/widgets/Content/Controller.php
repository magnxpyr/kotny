<?php
/**
 * Created by IntelliJ IDEA.
 * User: gatz
 * Date: 10.12.2016
 * Time: 22:06
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
            ->addFrom('Core\Models\Content', 'c')
            ->addFrom('Core\Models\Category', 'cat')
            ->addFrom('Core\Models\User', 'u')
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