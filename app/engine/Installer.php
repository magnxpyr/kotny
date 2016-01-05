<?php
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Engine;

use Engine\Behavior\DiBehavior;
use Engine\Mvc\Exception;

/**
 * Class Installer
 * @package Engine
 */
abstract class Installer
{
    use DiBehavior;

    public abstract function install();

    public abstract function update();

    public abstract function remove();

    /**
     * Execute SQL File
     * @param $filePath
     * @throws Exception
     */
    public function runSqlFile($filePath)
    {
        if (file_exists($filePath)) {
            $connection = $this->getDI()->get('db');
            $connection->begin();
            $connection->query(file_get_contents($filePath));
            $connection->commit();
        } else {
            throw new Exception(sprintf('Sql file "%s" does not exists', $filePath));
        }
    }

    public function getResources()
    {

    }
}