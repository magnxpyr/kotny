<?php
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 * @package     Engine
 */

namespace Engine\Package;

use Engine\Behavior\DiBehavior;
use Engine\Di\Injectable;
use Engine\Mvc\Exception;
use Phalcon\Db\Exception as DbException;

/**
 * Class Migration
 * @package Engine\Migration
 */
abstract class Migration extends Injectable
{
    use DiBehavior;

    const
        UP = "up",
        DOWN = "down";

    final public function __construct()
    {
        $this->getDI();
    }

    public function up() {}

    public function down() {}

    /**
     * Execute SQL File
     * @param $filePath
     * @throws Exception
     */
    public function runSqlFile($filePath)
    {
        if (file_exists($filePath)) {
            $connection = $this->db;
            $connection->begin();
            $connection->query(file_get_contents($filePath));
            $connection->commit();
        } else {
            throw new Exception(sprintf('Sql file "%s" does not exists', $filePath));
        }
    }

    /**
     * Execute SQL
     * @param $sql
     */
    public function runSql($sql)
    {
        $connection = $this->db;
        $connection->begin();
        $connection->query($sql);
        $connection->commit();
    }

    /**
     * Look for table definition modifications and apply to real table
     *
     * @param $tableName
     * @param $definition
     *
     * @throws \Phalcon\Db\Exception
     */
    public function morphTable($tableName, $definition)
    {
        $defaultSchema = null;
        $tableName = $this->getTableName($tableName);

        if (isset($this->db->dbname)) {
            $defaultSchema = $this->db->dbname;
        }

        $tableExists = $this->db->tableExists($tableName, $defaultSchema);
        if (isset($definition['columns'])) {
            if (count($definition['columns']) == 0) {
                throw new DbException('Table must have at least one column');
            }

            $fields = array();
            foreach ($definition['columns'] as $tableColumn) {
                if (!is_object($tableColumn)) {
                    throw new DbException('Table must have at least one column');
                }
                /** @var  \Phalcon\Db\ColumnInterface $tableColumn */
                $fields[$tableColumn->getName()] = $tableColumn;
            }

            if ($tableExists == true) {
                $localFields = array();
                /** @var  \Phalcon\Db\ColumnInterface[] $description */
                $description = $this->db->describeColumns($tableName, $defaultSchema);
                foreach ($description as $field) {
                    $localFields[$field->getName()] = $field;
                }

                foreach ($fields as $fieldName => $tableColumn) {
                    /**
                     * @var \Phalcon\Db\ColumnInterface $tableColumn
                     * @var \Phalcon\Db\ColumnInterface[] $localFields
                     */

                    if (!isset($localFields[$fieldName])) {
                        $this->db->addColumn($tableName, $tableColumn->getSchemaName(), $tableColumn);
                    } else {
                        $changed = false;

                        if ($localFields[$fieldName]->getType() != $tableColumn->getType()) {
                            $changed = true;
                        }

                        if ($localFields[$fieldName]->getSize() != $tableColumn->getSize()) {
                            $changed = true;
                        }

                        if ($tableColumn->isNotNull() != $localFields[$fieldName]->isNotNull()) {
                            $changed = true;
                        }

                        if ($tableColumn->getDefault() != $localFields[$fieldName]->getDefault()) {
                            $changed = true;
                        }

                        if ($changed == true) {
                            $this->db->modifyColumn($tableName, $tableColumn->getSchemaName(), $tableColumn);
                        }
                    }
                }

                foreach ($localFields as $fieldName => $localField) {
                    if (!isset($fields[$fieldName])) {
                        $this->db->dropColumn($tableName, null, $fieldName);
                    }
                }
            } else {
                $this->db->createTable($tableName, $defaultSchema, $definition);
                if (method_exists($this, 'afterCreateTable')) {
                    $this->afterCreateTable();
                }
            }
        }

        if (isset($definition['references'])) {
            if ($tableExists == true) {
                $references = array();
                foreach ($definition['references'] as $tableReference) {
                    $references[$tableReference->getName()] = $tableReference;
                }

                $localReferences = array();
                $activeReferences = $this->db->describeReferences($tableName, $defaultSchema);
                foreach ($activeReferences as $activeReference) {
                    $localReferences[$activeReference->getName()] = array(
                        'referencedTable' => $activeReference->getReferencedTable(),
                        'columns' => $activeReference->getColumns(),
                        'referencedColumns' => $activeReference->getReferencedColumns(),
                    );
                }

                foreach ($definition['references'] as $tableReference) {
                    if (!isset($localReferences[$tableReference->getName()])) {
                        $this->db->addForeignKey($tableName, $tableReference->getSchemaName(), $tableReference);
                    } else {
                        $changed = false;
                        if ($tableReference->getReferencedTable()!=$localReferences[$tableReference->getName()]['referencedTable']) {
                            $changed = true;
                        }

                        if ($changed == false) {
                            if (count($tableReference->getColumns()) != count($localReferences[$tableReference->getName()]['columns'])) {
                                $changed = true;
                            }
                        }

                        if ($changed==false) {
                            if (count($tableReference->getReferencedColumns()) != count($localReferences[$tableReference->getName()]['referencedColumns'])) {
                                $changed = true;
                            }
                        }
                        if ($changed == false) {
                            foreach ($tableReference->getColumns() as $columnName) {
                                if (!in_array($columnName, $localReferences[$tableReference->getName()]['columns'])) {
                                    $changed = true;
                                    break;
                                }
                            }
                        }
                        if ($changed == false) {
                            foreach ($tableReference->getReferencedColumns() as $columnName) {
                                if (!in_array($columnName, $localReferences[$tableReference->getName()]['referencedColumns'])) {
                                    $changed = true;
                                    break;
                                }
                            }
                        }

                        if ($changed == true) {
                            $this->db->dropForeignKey($tableName, $tableReference->getSchemaName(), $tableReference->getName());
                            $this->db->addForeignKey($tableName, $tableReference->getSchemaName(), $tableReference);
                        }
                    }
                }

                foreach ($localReferences as $referenceName => $reference) {
                    if (!isset($references[$referenceName])) {
                        $this->db->dropForeignKey($tableName, null, $referenceName);
                    }
                }
            }
        }

        if (isset($definition['indexes'])) {
            if ($tableExists == true) {
                $indexes = array();
                foreach ($definition['indexes'] as $tableIndex) {
                    $indexes[$tableIndex->getName()] = $tableIndex;
                }

                $localIndexes = array();
                $actualIndexes = $this->db->describeIndexes($tableName, $defaultSchema);
                foreach ($actualIndexes as $actualIndex) {
                    $localIndexes[$actualIndex->getName()] = $actualIndex->getColumns();
                }

                foreach ($definition['indexes'] as $tableIndex) {
                    if (!isset($localIndexes[$tableIndex->getName()])) {
                        if ($tableIndex->getName() == 'PRIMARY') {
                            $this->db->addPrimaryKey($tableName, $tableColumn->getSchemaName(), $tableIndex);
                        } else {
                            $this->db->addIndex($tableName, $tableColumn->getSchemaName(), $tableIndex);
                        }
                    } else {
                        $changed = false;
                        if (count($tableIndex->getColumns()) != count($localIndexes[$tableIndex->getName()])) {
                            $changed = true;
                        } else {
                            foreach ($tableIndex->getColumns() as $columnName) {
                                if (!in_array($columnName, $localIndexes[$tableIndex->getName()])) {
                                    $changed = true;
                                    break;
                                }
                            }
                        }
                        if ($changed == true) {
                            if ($tableIndex->getName() == 'PRIMARY') {
                                $this->db->dropPrimaryKey($tableName, $tableColumn->getSchemaName());
                                $this->db->addPrimaryKey($tableName, $tableColumn->getSchemaName(), $tableIndex);
                            } else {
                                $this->db->dropIndex($tableName, $tableColumn->getSchemaName(), $tableIndex->getName());
                                $this->db->addIndex($tableName, $tableColumn->getSchemaName(), $tableIndex);
                            }
                        }
                    }
                }
                foreach ($localIndexes as $indexName => $indexColumns) {
                    if (!isset($indexes[$indexName])) {
                        $this->db->dropIndex($tableName, null, $indexName);
                    }
                }
            }
        }
    }

//    /**
//     * Inserts data from a data Migrations file in a table
//     *
//     * @param string $tableName
//     * @param string $fields
//     */
//    public function batchInsertFromFile($tableName, $fields)
//    {
//        $migrationData = self::$_migrationPath.'/'.$tableName.'.dat';
//        if (file_exists($migrationData)) {
//            $this->db->begin();
//            $this->db->delete($tableName);
//            $batchHandler = fopen($migrationData, 'r');
//            while (($line = fgets($batchHandler)) !== false) {
//                $this->db->insert($tableName, explode('|', rtrim($line)), $fields, false);
//                unset($line);
//            }
//            fclose($batchHandler);
//            $this->db->commit();
//        }
//    }

    /**
     * Inserts multiple rows in a table
     *
     * @param string $tableName
     * @param array $values
     * @param array $fields
     */
    public function batchInsert($tableName, $values, $fields)
    {
        $tableName = $this->getTableName($tableName);
        $this->db->begin();
        foreach ($values as $value) {
            $this->db->insert($tableName, $value, $fields);
        }
        $this->db->commit();
    }

    public function getTableName($tableName)
    {
        return $this->config->dbPrefix . $tableName;
    }
}