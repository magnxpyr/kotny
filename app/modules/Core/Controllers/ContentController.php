<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Models\Category;
use Module\Core\Models\Content;
use Engine\Mvc\Controller;
use Module\Core\Models\User;
use Phalcon\Paginator\Adapter\QueryBuilder as PaginatorQueryBuilder;

/**
 * Class ContentController
 * @package Module\Core\Controllers
 */
class ContentController extends Controller
{
    /**
     * Show articles from a category
     *
     * @param $category
     * @param $page
     */
    public function categoryAction($category = "articles", $page)
    {
        $builder = $this->modelsManager->createBuilder()
            ->columns('u.*, c.*, cat.*')
            ->addFrom(Content::class, 'c')
            ->leftJoin(User::class, 'c.created_by = u.id', 'u');
            if ($category == "articles") {
                $builder->leftJoin(Category::class, 'cat.id = c.category', 'cat');
            } else {
                $builder->innerJoin(Category::class, 'c.category = cat.id', 'cat')
                    ->andWhere('cat.alias = :category:', ['category' => $category]);
            }
        $builder->orderBy('c.created_at DESC');

        $paginator = new PaginatorQueryBuilder([
            "builder"  => $builder,
            "limit" => 10,
            "page"  => $page ? $page : 1
        ]);

        $this->view->setVar('model', $paginator->getPaginate());
        $this->view->setVar('category', $category);
    }

    /**
     * Show an article
     *
     * @param $catAlias string
     * @param $articleId int
     * @param $articleAlias string
     */
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
            $model->cat->getAlias() != $catAlias || !$this->acl->checkViewLevel($model->viewLevel->getRolesArray())) {
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

        $meta = $model->c->getMetadataArray();
        if (!empty($meta)) {
            foreach ($meta as $key => $val) {
                if (!empty($val)) {
                    $this->view->setVar($key, $val);
                }
            }
            if (!isset($meta->metaTitle) || empty($meta->metaTitle)) {
                $this->view->setVar('metaTitle', $model->c->title);
            }
        }

        /**
         * @var Content $content
         */
        $content = $model->c;
        $content->setHits($content->getHits() + 1)->save();

        $this->view->setVar('model', $model);
    }
}