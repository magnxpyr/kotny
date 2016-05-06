<?php 
/**
 * @copyright   2006 - 2016 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Tools\Builder\Mvc\Model\Migration;

class WidgetMigration_101 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'widget',
            [
                'columns' => [
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
                    ]),
                    new Column('params', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'title'
                    ]),
                    new Column('description', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'params'
                    ]),
                    new Column('version', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 20,
                        'after' => 'description'
                    ]),
                    new Column('author', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 100,
                        'after' => 'version'
                    ]),
                    new Column('website', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'author'
                    ]),
                    new Column('status', [
                        'type' => 'TINYINT',
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'website'
                    ]),
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'])
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );
    }
}