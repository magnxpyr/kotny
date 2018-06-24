<?php 
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Engine\Package\Migration;

class Route20180517191323Migration extends Migration
{
    const TABLE_NAME = 'route';

    public function up()
    {
        $this->morphTable(
            self::TABLE_NAME,
            [
                'columns' => [
                    new Column('id', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 11,
                        'first' => true
                    ]),
                    new Column('name', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'id'
                    ]),
                    new Column('pattern', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'name'
                    ]),
                    new Column('method', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'pattern'
                    ]),
                    new Column('package_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'after' => 'method'
                    ]),
                    new Column('controller', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'package_id'
                    ]),
                    new Column('action', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'controller'
                    ]),
                    new Column('params', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'action'
                    ]),
                    new Column('ordering', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'params'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'ordering'
                    ])
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
