<?php 
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Tools\Builder\Mvc\Model\Migration;

class AccessListMigration_101 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'access_list',
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
                    new Column('role_id', [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 32,
                            'after' => 'id'
                    ]),
                    new Column('resource', [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 32,
                            'after' => 'role_id'
                    ]),
                    new Column('action', [
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 32,
                            'after' => 'resource'
                    ]),
                    new Column('status', [
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'action'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id', 'role_id', 'resource', 'action'])
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
