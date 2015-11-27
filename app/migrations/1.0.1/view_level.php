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

class ViewLevelMigration_101 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'view_level',
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
                        'size' => 64,
                        'after' => 'id'
                    ]),
                    new Column('roles', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'name'
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

        $this->batchInsert(
            'view_level',
            [
                [1, 'Public', json_encode([1,2,3])],
                [2, 'Guest', json_encode([1])],
                [3, 'Registered', json_encode([2,3])],
                [4, 'Administrator', json_encode([3])]
            ]
        );
    }
}
