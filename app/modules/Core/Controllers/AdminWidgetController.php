<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Module\Core\Forms\AdminWidgetEditForm;
use Module\Core\Models\Package;
use Module\Core\Models\Widget;
use DataTables\DataTable;
use Engine\Mvc\AdminController;
use Phalcon\Db\Column;
use Phalcon\Mvc\View;

/**
 * Class AdminWidgetController
 * @package Module\Core\Controllers
 */
class AdminWidgetController extends AdminController
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Widget');
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $builder = $this->modelsManager->createBuilder()
            ->columns('w.id, w.ordering, w.title, p.name as package, w.position, v.name as viewLevel, w.publish_up, w.status')
            ->addFrom('Module\Core\Models\Widget', 'w')
            ->addFrom('Module\Core\Models\ViewLevel', 'v')
            ->addFrom('Module\Core\Models\Package', 'p')
            ->where('w.view_level = v.id')
            ->andWhere('w.package_id = p.id')
            ->orderBy(['w.ordering']);

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function createAction()
    {
        $this->setTitle('Create Widget');
        $form = new AdminWidgetEditForm();
        $this->view->setVar('form', $form);

        if ($this->request->isPost()) {
            $form->get('ordering')->setOptions($this->getOrdering($_POST['position'], $_POST['id']));
        } else {
            $this->view->setVar('widgetContent', null);
            $this->view->setVar('widgetScripts', null);
            $form->get('ordering')->setOptions($this->getOrdering('menu', null));
            $form->get('cache')->setDefault(\Engine\Widget\Widget::CACHE_LIFETIME);
        }

        $this->view->render('admin-widget', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a menu
     *
     * @param int $id
     */
    public function editAction($id)
    {
        $this->setTitle('Edit Widget');
        $form = new AdminWidgetEditForm();
        $this->view->setVar('form', $form);
        if ($this->request->isGet()) {
            /** @var Widget $model */
            $model = Widget::findFirstById($id);
            if (!$model) {
                $this->flash->error("Widget was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }
            $model->setOrdering($model->getOrdering() - 1);
            $form->setEntity($model);

            $form->get('ordering')->setOptions($this->getOrdering($model->getPosition(), $id));
            $form->get('layout')->setOptions($this->getWidgetLayouts());

            /** @var Package $package */
            $package = Package::findFirstById($model->getPackageId());
            $widgetContent = null;
            $widgetScripts = null;
            if ($package) {
                $form->get('view')->setOptions($this->getWidgetViews($package->getName()));

                $widgetContent = $this->renderWidget([
                   'widgetName' => $package->getName(),
                   'widgetId' => $model->getId()
                ]);
                $widgetScripts = $this->renderWidgetScripts([
                   'widgetName' => $package->getName()
                ]);
            }
            $this->view->setVar('widgetContent', $widgetContent);
            $this->view->setVar('widgetScripts', $widgetScripts);
        } else {
            $form->get('ordering')->setOptions($this->getOrdering($_POST['position'], $_POST['id']));
        }
    }

    /**
     * Saves a menu edited
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminWidgetEditForm();
        $id = $this->request->getPost('id');
        if (!empty($id)) {
            $model = Widget::findFirstById($this->request->getPost('id'));
        } else {
            $model = new Widget();
        }

        $post = $_POST;
        if (isset($post['ordering'])) {
            $post['ordering'] += 1;
        }

        $form->bind($post, $model);
        if (!$form->isValid()) {
            $this->manageSaveErrors($form, $model, null);
            return;
        }

        $params = [];
        foreach ($post as $key => $value) {
            if (substr($key, 0, 1) === "_") {
                $params[$key] = $value;
            }
        }
        if (!empty($params)) {
            $model->setParams(json_encode($params));
        } else {
            $model->setParams(null);
        }

        /** @var Package $package */
        $package = Package::findFirstById($post['package_id']);
        if ($package) {
            $widgetFormClass = 'Widget\\' . $package->getName() . '\Forms\AdminIndexForm';
            if (class_exists($widgetFormClass)) {
                $widgetForm = new $widgetFormClass();

                $widgetForm->bind($params, $widgetForm);
                if (!$widgetForm->isValid()) {
                    $this->manageSaveErrors($form, $model, $package, $widgetForm);
                    return;
                }
            }
        }

        if (!$model->save()) {
            $this->flashErrors($model);

            $this->dispatcher->forward([
                "action" => "edit",
                "params" => [$id]
            ]);
            return;
        }

        $widgetsResult = Widget::find([
            'conditions' => 'position = ?1',
            'bind' => [1 => $model->getPosition()],
            'bindTypes' => [Column::BIND_PARAM_STR],
            'order' => 'ordering'
        ]);

        // fix order
        $order = 0;
        $widgets = [];
        foreach ($widgetsResult as $row) {
            if ($row->getId() == $model->getId()) {
                if ($row->getOrdering < $order) {
                    $widgets[$row->getOrdering()] = $row;
                } else {
                    $widgets[$order] = $row;
                }
                $order++;
                continue;
            } elseif ($model->getOrdering() == $row->getOrdering() && $order == $row->getOrdering()) {
                $order++;
                $widgets[$order] = $row;
                continue;
            }
            $widgets[$order] = $row;
            $order++;
        }

        // reindex order
        ksort($widgets);
        $order = 0;
        foreach ($widgets as $row) {
            $row->setOrdering($order);
            $row->save();
            $order++;
        }

        $this->cache->delete(Package::getCacheActiveWidgets());

        $this->flash->success("Widget was updated successfully");

        $this->response->redirect('admin/core/widget/index')->send();
        return;
    }

    /**
     * Deletes a widget
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $model = Widget::findFirstById($id);
        if (!$model) {
            return;
        }

        if (!$model->delete()) {
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }

    /**
     * Renders a widget admin area
     */
    public function renderWidgetAction()
    {
        if (!$this->request->isAjax() || !$this->request->isGet()) {
            return;
        }

        return $this->returnJSON([
            'html' => $this->renderWidget($_GET),
            'scripts' => $this->renderWidgetScripts($_GET),
            'layouts' => $this->getWidgetLayouts(),
            'views' => $this->getWidgetViews($_GET['widgetName']),
            'success' => true
        ]);
    }

    /**
     * Get widgets order by position
     */
    public function getOrderAction()
    {
        if (!$this->request->isAjax() || !$this->request->isGet()) {
            return;
        }

        return
            $this->returnJSON([
                'ordering' => $this->getOrdering($_GET['position'], $_GET['id']),
                'success' => true
            ]);
    }

    private function manageSaveErrors($form, $widget, $package, $widgetForm = null)
    {
        $this->flashErrors($form);
        if ($widgetForm != null) $this->flashErrors($widgetForm);

        $form->get('ordering')->setOptions($this->getOrdering($widget->getPosition(), $widget->getId()));

        $widgetContent = null;
        if ($package == null && $widget->getPackageId() != null) {
            $package = Package::findFirstById($widget->getPackageId());
        }
        if ($package) {
            $widgetContent = $this->renderWidget([
                'widgetName' => $package->getName(),
                'widgetId' => $widget->getId()
            ]);
        }
        $this->view->setVar('form', $form);
        $this->view->setVar('widgetContent', $widgetContent);

        $action = $widget->getId() ? 'edit' : 'create';

        $this->dispatcher->forward([
            "action" => $action,
            "params" => [$widget->getId()]
        ]);
    }

    /**
     * Get widgets ordering by position
     * @param $position
     * @param $id
     * @return array
     */
    private function getOrdering($position, $id)
    {
        $model = Widget::find([
            'conditions' => 'position = ?1',
            'bind' => [1 => $position],
            'bindTypes' => [Column::BIND_PARAM_STR],
            'order' => 'ordering'
        ]);

        $widgets = [-1 => '- First -'];
        foreach ($model as $row) {
            if ($row->getId() == $id) {
                continue;
            }
            $widgets[$row->getOrdering()] = $row->getTitle();
        }

        return $widgets;
    }

    /**
     * Renders a widget
     *
     * $this->section->renderWidget([
     *  'widgetName' => 'Menu',
     *  'widgetController' => 'controller',
     *  'widgetId' => 1
     * ]);
     *
     * @var $widget array
     * @return string
     */
    private function renderWidget($widget)
    {
        return $this->widget->render([
            'widget' => $widget['widgetName'],
            'controller' => 'admin-controller'
        ], [
            'id' => $widget['widgetId']
        ]);
    }

    private function renderWidgetScripts($widget)
    {
        return $this->widget->renderSimple([
            'widget' => $widget['widgetName'],
            'view' => 'admin-index-scripts'
        ]);
    }

    private function getWidgetLayouts()
    {
        $view = ['widget' => 'widget'];
        $files = glob($this->view->getLayoutsDir() . 'widget*.{volt,phtml}', GLOB_BRACE);
        foreach ($files as $file) {
            preg_match("/.*[\/|\\\](.*?)\..*/s", $file, $matches);
            if (isset($matches[1]) && $matches[1] != 'widget') {
                $view[$matches[1]] = $matches[1];
            }
        }
        return $view;
    }

    private function getWidgetViews($widget)
    {
        $view = [];
        $files = glob(WIDGETS_PATH . "$widget/Views/*.{volt,phtml}", GLOB_BRACE);
        foreach ($files as $file) {
            preg_match("/.*[\/|\\\](.*?)\..*/s", $file, $matches);
            if (isset($matches[1]) && substr($matches[1], 0, 6) != 'admin-' &&
                strpos($matches[1], '-scripts') === false) {
                $view[$matches[1]] = $matches[1];
            }
        }
        return $view;
    }
}