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

class RoleMigration extends Migration
{
    const TABLE_NAME = 'role';

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
                        'size' => 2,
                        'first' => true
                    ]),
                    new Column('parent_id', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 2,
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
                        'size' => 1,
                        'after' => 'name'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id', 'parent_id'])
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
            [0, 'guest', 'Guest user group'],
            [0, 'user', 'Common logged in user without additional access'],
            [0, 'admin', 'Administrators group with full access']
        ], ['parent_id', 'name', 'description']);
    }
}
