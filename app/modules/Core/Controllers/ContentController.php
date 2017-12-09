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
            ->columns('user.*, content.*, category.*')
            ->addFrom(Content::class, 'content')
            ->leftJoin(User::class, 'content.created_by = user.id', 'user');
            if ($category == "articles") {
                $builder->leftJoin(Category::class, 'category.id = content.category', 'category');
            } else {
                $builder->innerJoin(Category::class, 'content.category = category.id', 'category')
                    ->andWhere('category.alias = :category:', ['category' => $category]);
            }
        $builder->orderBy('content.created_at DESC');

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
        $this->view->setVar('metaDescription', $this->config->metaDesc);
        $this->view->setVar('metaKeywords', $this->config->metaKeys);

        $model = $this->modelsManager->createBuilder()
            ->columns('content.*, category.*, user.*, viewLevel.*')
            ->addFrom('Module\Core\Models\Content', 'content')
            ->addFrom('Module\Core\Models\Category', 'category')
            ->addFrom('Module\Core\Models\User', 'user')
            ->addFrom('Module\Core\Models\ViewLevel', 'viewLevel')
            ->andWhere('content.category = category.id')
            ->andWhere('content.created_by = user.id')
            ->andWhere('content.view_level = viewLevel.id')
            ->andWhere('content.id = :id:', ['id' => $articleId])
            ->getQuery()
            ->getSingleResult();

        // if empty show 404
        if (!$model || !$this->helper->isContentPublished($model->content) || $model->content->getAlias() != $articleAlias ||
            $model->category->getAlias() != $catAlias || !$this->acl->checkViewLevel($model->viewLevel->getRolesArray())) {
            $this->dispatcher->forward([
                'controller' => 'error',
                'action' => 'show404'
            ]);
            return;
        }

        $this->setTitle($model->content->title);

        if (!empty($model->user->name)) {
            $this->view->setVar('metaAuthor', $model->u->name);
        }

        $meta = $model->content->getMetadataArray();
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
        $content = $model->content;
        $content->setHits($content->getHits() + 1)->save();

        $this->view->setVar('model', $model);
    }
}