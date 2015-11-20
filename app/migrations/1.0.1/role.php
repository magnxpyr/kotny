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

class RoleMigration_101 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'role',
            [
                'columns' => [
                    new Column('id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 2,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'first' => true
                    ]),
                    new Column('parent_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 2,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]),
                    new Column('name', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 32,
                        'after' => 'parent_id'
                    ]),
                    new Column('description', [
                        'type' => Column::TYPE_TEXT,
                        'size' => 255,
                        'after' => 'name'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id', 'parent_id'])
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );

        $this->batchInsert(
            'role',
            [
                [1, 0, 'guest', null],
                [2, 0, 'user', null],
                [3, 0, 'admin', null]
            ]
        );
    }
}
