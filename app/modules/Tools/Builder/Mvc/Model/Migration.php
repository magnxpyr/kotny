<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2015 Phalcon Team (http://www.phalconphp.com)       |
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

namespace Module\Tools\Builder\Mvc\Model;

use Engine\Mvc\Config\ConfigSample;
use Module\Tools\Helpers\Tools;
use Phalcon\Db;
use Phalcon\Text;
use Phalcon\Db\Column;
use Phalcon\Db\Exception as DbException;

/**
 * Tools\Builder\Mvc\Model\Migration
 *
 * Migrations of DML y DDL over databases
 *
 * @package     Tools\Builder\Mvc\Model
 * @copyright   Copyright (c) 2011-2015 Phalcon Team (team@phalconphp.com)
 * @license     New BSD License
 */
class Migration
{
    /**
     * Migration database connection
     * @var \Phalcon\Db\AdapterInterface
     */
    protected static $_connection;

    /**
     * Database configuration
     * @var ConfigSample
     */
    private static $_databaseConfig;

    /**
     * Path where to save the Migrations
     * @var string
     */
    private static $_migrationPath = null;

    /**
     * Prepares component
     *
     * @param ConfigSample $config
     *
     * @throws \Phalcon\Db\Exception
     */
    public static function setup($config)
    {
        if (!isset($config->dbAdaptor)) {
            throw new DbException('Unspecified database Adapter in your configuration!');
        }

        $adapter = '\\Phalcon\\Db\\Adapter\\Pdo\\' . $config->dbAdaptor;

        if (!class_exists($adapter)) {
            throw new DbException('Invalid database Adapter!');
        }

        self::$_connection = Tools::getConnection();
        self::$_databaseConfig = $config;

        if ($config->dbAdaptor == 'mysql') {
            self::$_connection->query('SET FOREIGN_KEY_CHECKS=0');
        }
    }

    /**
     * Set the Migrations directory path
     *
     * @param string $path
     */
    public static function setMigrationPath($path)
    {
        self::$_migrationPath = $path;
    }

    /**
     * Generates all the class Migrations definitions for certain database setup
     *
     * @param  string $version
     * @param  string $exportData
     * @return array
     */
    public static function generateAll($version, $exportData = null)
    {
        $classDefinition = [];
        if (self::$_databaseConfig->dbAdaptor == 'postgresql') {
            $tables = self::$_connection->listTables(isset(self::$_databaseConfig->dbSchema) ? self::$_databaseConfig->dbSchema : 'public');
        } else {
            $tables = self::$_connection->listTables();
        }

        foreach ($tables as $table) {
            $classDefinition[$table] = self::generate($version, $table, $exportData);
        }

        return $classDefinition;
    }

    /**
     * Generate specified table Migrations
     *
     * @param      $version
     * @param      $table
     * @param null $exportData
     *
     * @return string
     * @throws \Phalcon\Db\Exception
     */
    public static function generate($version, $table, $exportData=null)
    {
        $oldColumn = null;
        $allFields = [];
        $numericFields = [];
        $tableDefinition = [];

        if (isset(self::$_databaseConfig->dbSchema)) {
            $defaultSchema = self::$_databaseConfig->dbSchema;
        } elseif (isset(self::$_databaseConfig->dbAdaptor) && self::$_databaseConfig->dbAdaptor == 'postgresql') {
            $defaultSchema =  'public';
        } elseif (isset(self::$_databaseConfig->dbName)) {
            $defaultSchema = self::$_databaseConfig->dbName;
        } else {
            $defaultSchema = null;
        }

        if(!@self::$_connection->tableExists($table, $defaultSchema)) {
            return self::generateEmpty([
                'version' => $version,
                'table' => $table,
                'defaultSchema' => $defaultSchema,
                'exportData' => $exportData
            ]);
        }

        $description = self::$_connection->describeColumns($table, $defaultSchema);

        foreach ($description as $field) {
            /** @var \Phalcon\Db\ColumnInterface $field */
            $fieldDefinition = [];
            switch ($field->getType()) {
                case Column::TYPE_INTEGER:
                    $fieldDefinition[] = "'type' => Column::TYPE_INTEGER";
                    $numericFields[ $field->getName() ] = true;
                    break;
                case Column::TYPE_VARCHAR:
                    $fieldDefinition[] = "'type' => Column::TYPE_VARCHAR";
                    break;
                case Column::TYPE_CHAR:
                    $fieldDefinition[] = "'type' => Column::TYPE_CHAR";
                    break;
                case Column::TYPE_DATE:
                    $fieldDefinition[] = "'type' => Column::TYPE_DATE";
                    break;
                case Column::TYPE_DATETIME:
                    $fieldDefinition[] = "'type' => Column::TYPE_DATETIME";
                    break;
                case 17: // If so, then Phalcon is support for Column::TYPE_TIMESTAMP constant
                    $fieldDefinition[] = "'type' => Column::TYPE_TIMESTAMP";
                    break;
                case Column::TYPE_DECIMAL:
                    $fieldDefinition[] = "'type' => Column::TYPE_DECIMAL";
                    $numericFields[ $field->getName() ] = true;
                    break;
                case Column::TYPE_TEXT:
                    $fieldDefinition[] = "'type' => Column::TYPE_TEXT";
                    break;
                case Column::TYPE_BOOLEAN:
                    $fieldDefinition[] = "'type' => Column::TYPE_BOOLEAN";
                    break;
                case Column::TYPE_FLOAT:
                    $fieldDefinition[] = "'type' => Column::TYPE_FLOAT";
                    break;
                case Column::TYPE_DOUBLE:
                    $fieldDefinition[] = "'type' => Column::TYPE_DOUBLE";
                    break;
                case Column::TYPE_TINYBLOB:
                    $fieldDefinition[] = "'type' => Column::TYPE_TINYBLOB";
                    break;
                case Column::TYPE_BLOB:
                    $fieldDefinition[] = "'type' => Column::TYPE_BLOB";
                    break;
                case Column::TYPE_MEDIUMBLOB:
                    $fieldDefinition[] = "'type' => Column::TYPE_MEDIUMBLOB";
                    break;
                case Column::TYPE_LONGBLOB:
                    $fieldDefinition[] = "'type' => Column::TYPE_LONGBLOB";
                    break;
                case Column::TYPE_JSON:
                    $fieldDefinition[] = "'type' => Column::TYPE_JSON";
                    break;
                case Column::TYPE_JSONB:
                    $fieldDefinition[] = "'type' => Column::TYPE_JSONB";
                    break;
                case Column::TYPE_BIGINTEGER:
                    $fieldDefinition[] = "'type' => Column::TYPE_BIGINTEGER";
                    break;
                default:
                    throw new DbException('Unrecognized data type ' . $field->getType() . ' at column ' . $field->getName());
            }

            if (null !== ($default = $field->getDefault())) {
                $fieldDefinition[] = "'default' => '$default'";
            }
            //if ($field->isPrimary()) {
            //	$fieldDefinition[] = "'primary' => true";
            //}

            if ($field->isUnsigned()) {
                $fieldDefinition[] = "'unsigned' => true";
            }

            if ($field->isNotNull()) {
                $fieldDefinition[] = "'notNull' => true";
            }

            if ($field->isAutoIncrement()) {
                $fieldDefinition[] = "'autoIncrement' => true";
            }

            if ($field->getSize()) {
                $fieldDefinition[] = "'size' => " . $field->getSize();
            } else {
                $fieldDefinition[] = "'size' => 1";
            }

            if ($field->getScale()) {
                $fieldDefinition[] = "'scale' => " . $field->getScale();
            }

            if ($oldColumn != null) {
                $fieldDefinition[] = "'after' => '" . $oldColumn . "'";
            } else {
                $fieldDefinition[] = "'first' => true";
            }

            $oldColumn = $field->getName();
            $tableDefinition[] = "\t\t\t\t\tnew Column('" . $field->getName() . "', [\n\t\t\t\t\t\t\t" . join(",\n\t\t\t\t\t\t\t", $fieldDefinition) . "\n\t\t\t\t\t\t]\n\t\t\t\t\t)";
            $allFields[] = "'".$field->getName()."'";
        }

        $indexesDefinition = [];
        $indexes = self::$_connection->describeIndexes($table, $defaultSchema);
        foreach ($indexes as $indexName => $dbIndex) {
            $indexDefinition = [];
            foreach ($dbIndex->getColumns() as $indexColumn) {
                $indexDefinition[] = "'" . $indexColumn . "'";
            }
            $indexesDefinition[] = "\t\t\t\t\tnew Index('".$indexName."', [" . join(", ", $indexDefinition) . "])";
        }

        $referencesDefinition = [];
        $references = self::$_connection->describeReferences($table, $defaultSchema);
        foreach ($references as $constraintName => $dbReference) {
            $columns = [];
            foreach ($dbReference->getColumns() as $column) {
                $columns[] = "'" . $column . "'";
            }

            $referencedColumns = [];
            foreach ($dbReference->getReferencedColumns() as $referencedColumn) {
                $referencedColumns[] = "'" . $referencedColumn . "'";
            }

            $referenceDefinition = [];
            $referenceDefinition[] = "'referencedSchema' => '" . $dbReference->getReferencedSchema() . "'";
            $referenceDefinition[] = "'referencedTable' => '" . $dbReference->getReferencedTable() . "'";
            $referenceDefinition[] = "'columns' => [" . join(",", $columns) . "]";
            $referenceDefinition[] = "'referencedColumns' => [".join(",", $referencedColumns) . "]";

            $referencesDefinition[] = "\t\t\t\t\tnew Reference('" . $constraintName."', [\n\t\t\t\t\t\t" . join(",\n\t\t\t\t\t", $referenceDefinition) . "\n\t\t\t\t\t])";
        }

        $optionsDefinition = [];
        $tableOptions = self::$_connection->tableOptions($table, $defaultSchema);
        foreach ($tableOptions as $optionName => $optionValue) {
            if(strtoupper($optionName) == "AUTO_INCREMENT") {
                $optionValue = '';
            }
            $optionsDefinition[] = "\t\t\t\t\t'" . strtoupper($optionName) . "' => '" . $optionValue . "'";
        }

        $className = Text::camelize($table) . $version . 'Migration';
        $classData = "use Phalcon\\Db\\Column;
use Phalcon\\Db\\Index;
use Phalcon\\Db\\Reference;
use Engine\\Package\\Migration;

class ".$className." extends Migration\n{\n".
        "\tconst TABLE_NAME = '$table';\n\n" .
        "\tpublic function up()\n\t{\n".
        "\t\t\$this->morphTable(\n\t\t\tself::TABLE_NAME,\n\t\t\t[" .
        "\n\t\t\t\t'columns' => [\n" . join(",\n", $tableDefinition) . "\n\t\t\t\t],";
        if (count($indexesDefinition)) {
            $classData .= "\n\t\t\t\t'indexes' => [\n" . join(",\n", $indexesDefinition) . "\n\t\t\t\t],";
        }

        if (count($referencesDefinition)) {
            $classData .= "\n\t\t\t\t'references' => [\n" . join(",\n", $referencesDefinition) . "\n\t\t\t\t],";
        }

        if (count($optionsDefinition)) {
            $classData .= "\n\t\t\t\t'options' => [\n" . join(",\n", $optionsDefinition) . "\n\t\t\t\t]\n";
        }

        $classData .= "\t\t\t]\n\t\t);\n\t}";
        if ($exportData == 'always' || $exportData == 'oncreate') {
            if ($exportData == 'oncreate') {
                $classData .= "\n\tpublic function afterCreateTable()\n\t{\n";
            } else {
                $classData .= "\n\tpublic function afterUp()\n\t{\n";
            }
            $classData .= "\t\t\$this->batchInsertFromFile(self::TABLE_NAME, [\n\t\t\t" . join(",\n\t\t\t", $allFields) . "\n\t\t]);";

            $fileHandler = fopen(self::$_migrationPath . '/' . $table . '.dat', 'w');
            $cursor = self::$_connection->query('SELECT * FROM ' . $table);
            $cursor->setFetchMode(Db::FETCH_ASSOC);
            while ($row = $cursor->fetchArray()) {
                $data = [];
                foreach ($row as $key => $value) {
                    if (isset($numericFields[$key])) {
                        if ($value==='' || is_null($value)) {
                            $data[] = 'NULL';
                        } else {
                            $data[] = addslashes($value);
                        }
                    } else {
                        $data[] = "'".addslashes($value)."'";
                    }
                    unset($value);
                }
                fputs($fileHandler, join('|', $data).PHP_EOL);
                unset($row);
                unset($data);
            }
            fclose($fileHandler);

            $classData.="\n\t}";
        }
        $classData.="\n}\n";
        $classData = str_replace("\t", "    ", $classData);

        return $classData;
    }

    private static function generateEmpty($options) {
        $className = Text::camelize($options['table']) . $options['version'] . 'Migration';
        $classData = "use Phalcon\\Db\\Column;
use Phalcon\\Db\\Index;
use Phalcon\\Db\\Reference;
use Engine\\Package\\Migration;

class ".$className." extends Migration\n{\n".
            "\tconst TABLE_NAME = '" . $options['table'] . "';\n\n" .
            "\tpublic function up()\n\t{\n".
            "\t\t\$this->morphTable(\n\t\t\tself::TABLE_NAME,\n\t\t\t[" .
            "\n\t\t\t\t'columns' => [
                    new Column('id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'first' => true
                    ]),
                    new Column('name', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'id'
                    ]),\n\t\t\t\t],";

        $classData .= "\n\t\t\t\t'indexes' => [\n\t\t\t\t\tnew Index('PRIMARY', ['id'])\n\t\t\t\t],";

        $classData .= "\n\t\t\t\t'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'\n\t\t\t\t]\n";

        $classData .= "\t\t\t]\n\t\t);\n\t}";

        $classData.="\n}\n";
        $classData = str_replace("\t", "    ", $classData);
        return $classData;
    }

    /**
     * Migrate single file
     *
     * @param $version
     * @param $filePath
     *
     * @throws \Phalcon\Db\Exception
     */
    public static function migrateFile($version, $filePath)
    {
        if (file_exists($filePath)) {
            $fileName = basename($filePath);
            $classVersion = preg_replace('/[^0-9A-Za-z]/', '', $version);
            $className = Text::camelize(str_replace('.php', '', $fileName)).'Migration';
            require_once $filePath;

            if (!class_exists($className)) {
                throw new DbException('Migration class cannot be found ' . $className . ' at ' . $filePath);
            }

            $migration = new $className();
            if (method_exists($migration, 'up')) {
                $migration->up();
                if (method_exists($migration, 'afterUp')) {
                    $migration->afterUp();
                }
            }
        }
    }
}