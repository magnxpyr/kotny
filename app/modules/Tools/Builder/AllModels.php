<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2014 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  +------------------------------------------------------------------------+
*/
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Module\Tools\Builder;

use Phalcon\Text;
use Module\Tools\Helpers\Tools;

/**
 * Class AllModels
 * @package Tools\Builder
 */
class AllModels extends Component
{
    public $exist = array();

    /**
     * AllModels Construct
     *
     * @param $options
     * @throws \Exception
     */
    public function __construct($options)
    {
        if (empty($options['name'])) {
            $options['name'] = Text::camelize($options['tableName']);
        }
        if (empty($options['directory'])) {
            $options['directory'] = Tools::getModulesPath() . $options['module'] .DIRECTORY_SEPARATOR. Tools::getModelsDir();
        }
        if (empty($options['namespace']) || $options['namespace'] != 'None') {
            if(empty($options['module']))
                $options['namespace'] = Tools::getBaseModule() . Tools::getModelsDir();
            else
                $options['namespace'] = Tools::getBaseModule() . $options['module'] . '\\' . Tools::getModelsDir();
        }
        if (empty($options['baseClass'])) {
            $options['baseClass'] = 'Engine\Mvc\Model';
        }
        if (empty($options['tableName'])) {
            throw new \Exception("Please, specify the table name");
        }
        if (!isset($options['force'])) {
            $options['force'] = false;
        }
        $this->_options = $options;
    }

    /**
     * Build Models
     * @throws \Exception
     */
    public function build()
    {
        if (isset($this->_options['defineRelations'])) {
            $defineRelations = $this->_options['defineRelations'];
        } else {
            $defineRelations = false;
        }

        if (isset($this->_options['foreignKeys'])) {
            $defineForeignKeys = $this->_options['foreignKeys'];
        } else {
            $defineForeignKeys = false;
        }

        if (isset($this->_options['genSettersGetters'])) {
            $genSettersGetters = $this->_options['genSettersGetters'];
        } else {
            $genSettersGetters = false;
        }

        $dbConfig = Tools::getConfig();

        /**
         * @var $db \Phalcon\Db\Adapter\Pdo
         */
        $db = Tools::getConnection();

        if (isset($this->_options['schema'])) {
            $schema = $this->_options['schema'];
        } elseif ($dbConfig->dbAdaptor == 'Postgresql') {
            $schema = 'public';
        } else {
            $schema = isset($dbConfig->schema) ? $dbConfig->schema : $dbConfig->dbName;
        }

        $hasMany = array();
        $belongsTo = array();
        $foreignKeys = array();
        if ($defineRelations || $defineForeignKeys) {
            foreach ($db->listTables($schema) as $name) {
                if ($defineRelations) {
                    if (!isset($hasMany[$name])) {
                        $hasMany[$name] = array();
                    }
                    if (!isset($belongsTo[$name])) {
                        $belongsTo[$name] = array();
                    }
                }
                if ($defineForeignKeys) {
                    $foreignKeys[$name] = array();
                }

                $camelCaseName = Text::camelize($name);
                $refSchema = ($dbConfig->dbAdaptor != 'Postgresql') ? $schema : $dbConfig->dbName;

                foreach ($db->describeReferences($name, $schema) as $reference) {
                    $columns = $reference->getColumns();
                    $referencedColumns = $reference->getReferencedColumns();
                    $referencedModel = Text::camelize($reference->getReferencedTable());
                    if ($defineRelations) {
                        if ($reference->getReferencedSchema() == $refSchema) {
                            if (count($columns) == 1) {
                                $belongsTo[$name][] = array(
                                    'referencedModel' => $referencedModel,
                                    'fields' => $columns[0],
                                    'relationFields' => $referencedColumns[0],
                                    'options' => $defineForeignKeys ? array('foreignKey'=>true) : NULL
                                );
                                $hasMany[$reference->getReferencedTable()][] = array(
                                    'camelizedName' => $camelCaseName,
                                    'fields' => $referencedColumns[0],
                                    'relationFields' => $columns[0]
                                );
                            }
                        }
                    }
                }
            }
        } else {
            foreach ($db->listTables($schema) as $name) {
                if ($defineRelations) {
                    $hasMany[$name] = array();
                    $belongsTo[$name] = array();
                    $foreignKeys[$name] = array();
                }
            }
        }

        foreach ($db->listTables($schema) as $name) {
            $className = Text::camelize($name);
            if (!file_exists($this->_options['directory'] . DIRECTORY_SEPARATOR . $className . '.php') || $this->_options['force']) {

                if (isset($hasMany[$name])) {
                    $hasManyModel = $hasMany[$name];
                } else {
                    $hasManyModel = array();
                }

                if (isset($belongsTo[$name])) {
                    $belongsToModel = $belongsTo[$name];
                } else {
                    $belongsToModel = array();
                }

                if (isset($foreignKeys[$name])) {
                    $foreignKeysModel = $foreignKeys[$name];
                } else {
                    $foreignKeysModel = array();
                }

                $modelBuilder = new Model(array(
                    'module' => $this->_options['module'],
                    'name' => $className,
                    'tableName' => $name,
                    'schema' => $schema,
                    'baseClass' => $this->_options['baseClass'],
                    'namespace' => $this->_options['namespace'],
                    'force' => $this->_options['force'],
                    'hasMany' => $hasManyModel,
                    'belongsTo' => $belongsToModel,
                    'foreignKeys' => $foreignKeysModel,
                    'genSettersGetters' => $genSettersGetters,
                    'directory' => $this->_options['directory'],
                ));

                $modelBuilder->build();
            } else {
                $this->exist[] = $className;
            }
        }
    }
}
