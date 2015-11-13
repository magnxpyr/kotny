<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use DataTables\DataTable;
use Core\Models\User;
use Engine\Mvc\AdminController;
use Phalcon\Paginator\Pager;

class AdminUserController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ['actions' => ['index', 'search', 'new', 'edit', 'create', 'save', 'delete']];
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Users');
    }

    public function searchAction()
    {
        if ($this->request->isAjax()) {
            $builder = $this->modelsManager->createBuilder()
                ->columns('id, username, email, role_id, status')
                ->from('Core\Models\User');

            $dataTables = new DataTable();
            $dataTables->fromBuilder($builder)->sendResponse();
        }
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a user
     *
     * @param string $id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $user = User::findFirst($id);
            if (!$user) {
                $this->flash->error("user was not found");

                return $this->dispatcher->forward(array(
                    "action" => "index"
                ));
            }

            $this->view->id = $user->id;

            $this->tag->setDefault("id", $user->getId());
            $this->tag->setDefault("username", $user->getUsername());

            
        }
    }

    /**
     * Creates a new user
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $user = new User();

        $user->setUsername($this->request->getPost("username"));
        $user->setAuthKey($this->request->getPost("auth_key"));
        $user->setHashKey($this->request->getPost("hash_key"));
        $user->setPassword($this->request->getPost("password"));
        $user->setResetToken($this->request->getPost("reset_token"));
        $user->setEmail($this->request->getPost("email", "email"));
        $user->setRole($this->request->getPost("role"));
        $user->setStatus($this->request->getPost("status"));
        $user->setRegisterDate($this->request->getPost("register_date"));
        $user->setLastVisitDate($this->request->getPost("last_visit_date"));
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "new"
            ));
        }

        $this->flash->success("user was created successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

    /**
     * Saves a user edited
     *
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $user = User::findFirst($id);
        if (!$user) {
            $this->flash->error("user does not exist " . $id);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $user->setUsername($this->request->getPost("username"));
        $user->setAuthKey($this->request->getPost("auth_key"));
        $user->setHashKey($this->request->getPost("hash_key"));
        $user->setPassword($this->request->getPost("password"));
        $user->setResetToken($this->request->getPost("reset_token"));
        $user->setEmail($this->request->getPost("email", "email"));
        $user->setRole($this->request->getPost("role"));
        $user->setStatus($this->request->getPost("status"));
        $user->setRegisterDate($this->request->getPost("register_date"));
        $user->setLastVisitDate($this->request->getPost("last_visit_date"));
        

        if (!$user->save()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($user->id)
            ));
        }

        $this->flash->success("user was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

    /**
     * Deletes a user
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $user = User::findFirst($id);
        if (!$user) {
            $this->flash->error("user was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$user->delete()) {
            foreach ($user->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "search"
            ));
        }

        $this->flash->success("user was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }
}
