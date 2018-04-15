<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use Engine\Mvc\AdminController;
use GuillermoMartinez\Filemanager\Filemanager;
use Phalcon\Mvc\View;

/**
 * Class AdminFileManagerController
 * @package Admin\Controllers
 */
class AdminFileManagerController extends AdminController
{
    /**
     * Display File Manager
     */
    public function indexAction()
    {
        $this->setTitle('File Manager');
    }

    public function basicAction()
    {
        $this->setTitle('File Manager');
        $this->view->setLayout(null);
    }

    /**
     * File manager connector
     */
    public function connectorAction()
    {
        $this->view->disable();
        $extra = [
            "doc_root" => PUBLIC_PATH,
            "source" => "media",
            "url" => $this->url->getBaseUri(),
            "images" => [
                'resize'=> [
                    'large' => [1024,768,true,true],
                ]
            ],
            // to show thumbs directory you need to set a valid directory and set show_folder_thumb on true
            "folder_thumb" => 'thumbs',
            "show_folder_thumb" => true
        ];
        if (isset($_POST['typeFile']) && $_POST['typeFile'] == 'images') {
            $extra['type_file'] = 'images';
        }
        $f = new Filemanager($extra);
        $f->run();
        $this->response->setContentType('text/html', 'UTF-8');
    }
}