<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Core\Controllers;

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Engine\Mvc\Controller;
use Core\Models\Role;

/**
 * Class RoleController
 * @package Core\Controllers
 */
class RoleController extends Controller
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for role
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Role", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $role = Role::find($parameters);
        if (count($role) == 0) {
            $this->flash->notice("The search did not find any role");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $role,
            "limit"=> 10,
            "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Displays the creation form
     */
    public function newAction()
    {

    }

    /**
     * Edits a role
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $role = Role::findFirstById($id);
            if (!$role) {
                $this->flash->error("role was not found");

                return $this->dispatcher->forward(array(
                    "action" => "index"
                ));
            }
            $this->view->id = $role->id;

            $this->tag->setDefault("id", $role->getId());
            $this->tag->setDefault("parent_id", $role->getParentId());
            $this->tag->setDefault("name", $role->getName());
            $this->tag->setDefault("description", $role->getDescription());
            
        }
    }

    /**
     * Creates a new role
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $role = new Role();

        $role->setParentId($this->request->getPost("parent_id", "string"));
        $role->setName($this->request->getPost("name", "string"));
        $role->setDescription($this->request->getPost("description", "string"));
        

        if (!$role->save()) {
            foreach ($role->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "new"
            ));
        }

        $this->flash->success("role was created successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

    /**
     * Saves a role edited
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $role = Role::findFirstById($id);
        if (!$role) {
            $this->flash->error("role does not exist " . $id);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $role->setParentId($this->request->getPost("parent_id", "string"));
        $role->setName($this->request->getPost("name", "string"));
        $role->setDescription($this->request->getPost("description", "string"));
        

        if (!$role->save()) {
            foreach ($role->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($role->id)
            ));
        }

        $this->flash->success("role was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

    /**
     * Deletes a role
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $role = Role::findFirstById($id);
        if (!$role) {
            $this->flash->error("role was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$role->delete()) {
            foreach ($role->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "search"
            ));
        }

        $this->flash->success("role was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }
}
