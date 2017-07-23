<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Core\Controllers;

use DataTables\DataTable;
use Engine\Mvc\AdminController;

class AdminCacheController extends AdminController
{
    /**
     * Index action
     */
    public function indexAction()
    {
        $this->setTitle('Cache Administration');
    }

    public function searchAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $keys = $this->cache->queryKeys();
        $model = [];
        foreach ($keys as $key) {
            $model[]['id'] = $key;
        }

        $dataTables = new DataTable();
        $dataTables->fromArray($model)->sendResponse();
    }

    /**
     * Deletes a cache entry
     *
     * @param string $id
     */
    public function deleteAction($id)
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $success = true;
        if (!$this->cache->delete($id)) {
            $success = false;
        }

        $this->returnJSON(['success' => $success]);
        return;
    }

    /**
     * Delete all items from the cache
     */
    public function flushCacheAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        $keys = $this->cache->queryKeys();

        foreach ($keys as $key) {
            $this->cache->delete($key);
        }

        $this->returnJSON(['success' => true]);
        return;
    }

    public function flushVoltCacheAction()
    {
        if (!$this->request->isAjax() || !$this->request->isPost()) {
            return;
        }

        array_map('unlink', glob(CACHE_PATH . 'volt/*'));

        $this->returnJSON(['success' => true]);
        return;
    }
}