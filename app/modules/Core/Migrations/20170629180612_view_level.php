<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Engine\Package\Migration;
use Phalcon\Db\Column;
use Phalcon\Db\Index;

class ViewLevelMigration extends Migration
{
    const TABLE_NAME = 'view_level';

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
                        'size' => 64,
                        'after' => 'id'
                    ]),
                    new Column('roles', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'name'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'])
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );

        $this->batchInsert(self::TABLE_NAME, [
            ['Public', json_encode([1, 2, 3])],
            ['Guest', json_encode([1])],
            ['Registered', json_encode([2, 3])],
            ['Administrator', json_encode([3])]
        ], ['name', 'roles']);
    }
}
