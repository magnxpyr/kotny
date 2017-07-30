<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Core\Models\Content;
use Engine\Mvc\Controller;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

/**
 * Class ContentController
 * @package Core\Controllers
 */
class ContentController extends Controller
{
    /**
     * Index action
     */
    public function categoryAction($category, $page)
    {
        $builder = $this->modelsManager->createBuilder()
            ->columns('content.*, category.*, user.*')
            ->addFrom('Core\Models\Content', 'content')
            ->addFrom('Core\Models\Category', 'category')
            ->addFrom('Core\Models\User', 'user')
            ->andWhere('content.category = category.id')
            ->andWhere('content.created_by = user.id')
            ->orderBy('content.created_at DESC');
            if ($category) {
                $builder->andWhere('category.alias = :category:', ['category' => $category]);
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
            ->columns('content.*, category.*, user.*, viewLevel.*')
            ->addFrom('Core\Models\Content', 'content')
            ->addFrom('Core\Models\Category', 'category')
            ->addFrom('Core\Models\User', 'user')
            ->addFrom('Core\Models\ViewLevel', 'viewLevel')
            ->andWhere('content.category = category.id')
            ->andWhere('content.created_by = user.id')
            ->andWhere('content.view_level = viewLevel.id')
            ->andWhere('content.id = :id:', ['id' => $articleId])
            ->getQuery()
            ->getSingleResult();

        // if empty show 404
        if (!$model || $model->content->getAlias() != $articleAlias || $model->category->getAlias() != $catAlias ||
            !$this->acl->checkViewLevel($model->viewLevel->getRoles())) {
            $this->dispatcher->forward([
                'controller' => 'error',
                'action' => '404'
            ]);
            return;
        }

        $this->setTitle($model->content->title);

        if (!empty($model->user->name)) {
            $this->view->setVar('metaAuthor', $model->u->name);
        }
        if (!empty($model->content->meta)) {
            $this->view->setVar('metaKeywords', $model->c->meta);
        }

        /**
         * @var Content $content
         */
        $content = $model->content;
        $content->setHits($content->getHits() + 1)->save();

        $this->view->setVar('model', $model);
    }
}