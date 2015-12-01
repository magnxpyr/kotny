<?php$copyright$
$namespace$

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Mvc\View;
use Phalcon\Paginator\Pager;
use DataTables\DataTable;
use $controllerClass$;
use $modelClass$;

/**
 * Class $className$Controller
 * @package $package$
 */
class $className$Controller extends $controllerName$
{
    /**
     * @inheritdoc
     */
    public function behaviors() {}

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('$plural$');
    }

    /**
     * Searches for $plural$
     */
    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $builder = $this->modelsManager->createBuilder()
            ->columns('u.id, u.username, u.email, r.name, u.status')
            ->addFrom('Core\Models\User', 'u')
            ->addFrom('Core\Models\Role', 'r')
            ->where('u.role_id = r.id');

        $dataTables = new DataTable();
        $dataTables->fromBuilder($builder)->sendResponse();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {
        $this->setTitle('Create User');
        $form = new AdminUserEditForm();
        $this->view->setVar('form', $form);
        $this->view->render('admin-user', 'edit');
        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
    }

    /**
     * Edits a $singular$
     *
     * @param string $pkVar$
     */
    public function editAction($pkVar$)
    {
        $this->setTitle('Edit User');
        $form = new AdminUserEditForm();
        $this->view->setVar('form', $form);
        if (!$this->request->isPost()) {
            $model = User::findFirstById($id);
            if (!$model) {
                $this->flash->error("User was not found");

                $this->dispatcher->forward([
                    "action" => "index"
                ]);
                return;
            }

            $this->tag->setDefault("id", $model->getId());
            $this->tag->setDefault("username", $model->getUsername());
            $this->tag->setDefault("password", $model->getPassword());
            $this->tag->setDefault("email", $model->getEmail());
            $this->tag->setDefault("role_id", $model->getRoleId());
            $this->tag->setDefault("status", $model->getStatus());
        }
    }

    /**
     * Saves a $singular$ edited
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            $this->dispatcher->forward([
                "action" => "index"
            ]);
            return;
        }

        $form = new AdminUserEditForm();
        if (!empty($this->request->getPost('id'))) {
            $menu = User::findFirstById($this->request->getPost('id'));
        } else {
            $menu = new User();
        }

        $form->bind($this->request->getPost(), $menu);
        if (!$form->isValid()) {
            $this->flashErrors($form);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
            return;
        }

        if (!$menu->save()) {
            $this->flashErrors($menu);

            $this->dispatcher->forward([
                "action" => "new"
            ]);
            return;
        }

        $this->flash->success("Menu was updated successfully");

        $this->response->redirect('admin/core/user/index')->send();
        return;
    }

    /**
     * Deletes a $singular$
     *
     * @param string $pkVar$
     */
    public function deleteAction($pkVar$)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }
        $menuType = User::findFirstById($id);
        if (!$menuType) {
            return;
        }

        if (!$menuType->delete()) {
            return;
        }

        $this->returnJSON(['success' => true]);
        return;
    }
}
