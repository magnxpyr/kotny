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
use Core\Models\ViewLevel;

/**
 * Class ViewLevelController
 * @package Core\Controllers
 */
class ViewLevelController extends Controller
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->persistent->parameters = null;
    }

    /**
     * Searches for view_level
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "ViewLevel", $_POST);
            $this->persistent->parameters = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = $this->persistent->parameters;
        if (!is_array($parameters)) {
            $parameters = array();
        }
        $parameters["order"] = "id";

        $view_level = ViewLevel::find($parameters);
        if (count($view_level) == 0) {
            $this->flash->notice("The search did not find any view_level");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $paginator = new Paginator(array(
            "data" => $view_level,
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
     * Edits a view_level
     *
     * @param string $id
     */
    public function editAction($id)
    {
        if (!$this->request->isPost()) {
            $view_level = ViewLevel::findFirstById($id);
            if (!$view_level) {
                $this->flash->error("view_level was not found");

                return $this->dispatcher->forward(array(
                    "action" => "index"
                ));
            }
            $this->view->id = $view_level->id;

            $this->tag->setDefault("id", $view_level->getId());
            $this->tag->setDefault("name", $view_level->getName());
            $this->tag->setDefault("roles", $view_level->getRoles());
            
        }
    }

    /**
     * Creates a new view_level
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $view_level = new ViewLevel();

        $view_level->setName($this->request->getPost("name", "string"));
        $view_level->setRoles($this->request->getPost("roles", "string"));
        

        if (!$view_level->save()) {
            foreach ($view_level->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "new"
            ));
        }

        $this->flash->success("view_level was created successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

    /**
     * Saves a view_level edited
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $id = $this->request->getPost("id");

        $view_level = ViewLevel::findFirstById($id);
        if (!$view_level) {
            $this->flash->error("view_level does not exist " . $id);

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        $view_level->setName($this->request->getPost("name", "string"));
        $view_level->setRoles($this->request->getPost("roles", "string"));
        

        if (!$view_level->save()) {
            foreach ($view_level->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "edit",
                "params" => array($view_level->id)
            ));
        }

        $this->flash->success("view_level was updated successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }

    /**
     * Deletes a view_level
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        $view_level = ViewLevel::findFirstById($id);
        if (!$view_level) {
            $this->flash->error("view_level was not found");

            return $this->dispatcher->forward(array(
                "action" => "index"
            ));
        }

        if (!$view_level->delete()) {
            foreach ($view_level->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(array(
                "action" => "search"
            ));
        }

        $this->flash->success("view_level was deleted successfully");

        return $this->dispatcher->forward(array(
            "action" => "index"
        ));
    }
}
