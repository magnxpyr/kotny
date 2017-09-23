<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Models\Content;
use Engine\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

/**
 * Class ContentController
 * @package Module\Core\Controllers
 */
class ContentController extends Controller
{
    /**
     * Index action
     */
    public function categoryAction($category, $page)
    {
        $builder = $this->modelsManager->createBuilder()
            ->columns('c.*, cat.*, u.*')
            ->addFrom('Module\Core\Models\Content', 'c')
            ->addFrom('Module\Core\Models\Category', 'cat')
            ->addFrom('Module\Core\Models\User', 'u')
            ->andWhere('c.category = cat.id')
            ->andWhere('c.created_by = u.id')
            ->orderBy('c.created_at DESC');
            if ($category) {
                $builder->andWhere('cat.alias = :category:', ['category' => $category]);
            }

        $paginator = new PaginatorQueryBuilder([
            "builder"  => $builder,
            "limit" => 10,
            "page"  => $page ? $page : 1
        ]);

        $this->view->setVar('model', $paginator->getPaginate());
        $this->view->setVar('category', $category);
    }

    public function articleAction($catAlias, $articleId, $articleAlias)
    {
        $this->view->setVar('metaAuthor', '');
        $this->view->setVar('metaDescription', $this->config->app->meta->description);
        $this->view->setVar('metaKeywords', $this->config->app->meta->keywords);

        $model = $this->modelsManager->createBuilder()
            ->columns('c.*, cat.*, u.*, viewLevel.*')
            ->addFrom('Module\Core\Models\Content', 'c')
            ->addFrom('Module\Core\Models\Category', 'cat')
            ->addFrom('Module\Core\Models\User', 'u')
            ->addFrom('Module\Core\Models\ViewLevel', 'viewLevel')
            ->andWhere('c.category = cat.id')
            ->andWhere('c.created_by = u.id')
            ->andWhere('c.view_level = viewLevel.id')
            ->andWhere('c.id = :id:', ['id' => $articleId])
            ->getQuery()
            ->getSingleResult();

        // if empty show 404
        if (!$model || !$this->helper->isContentPublished($model->c) || $model->c->getAlias() != $articleAlias ||
            $model->cat->getAlias() != $catAlias || !$this->acl->checkViewLevel($model->viewLevel->getRoles())) {
            $this->dispatcher->forward([
                'controller' => 'error',
                'action' => 'show404'
            ]);
            return;
        }

        $this->setTitle($model->c->title);

        if (!empty($model->u->name)) {
            $this->view->setVar('metaAuthor', $model->u->name);
        }
        if (!empty($model->c->meta)) {
            $this->view->setVar('metaKeywords', $model->c->meta);
        }

        /**
         * @var Content $content
         */
        $content = $model->c;
        $content->setHits($content->getHits() + 1)->save();

        $this->view->setVar('model', $model);
    }
}