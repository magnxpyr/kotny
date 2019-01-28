<?php
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Engine\Package\Migration;

class User20170629180556Migration extends Migration
{
    const TABLE_NAME = 'user';

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
                    new Column('username', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 50,
                        'after' => 'id'
                    ]),
                    new Column('email', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'username'
                    ]),
                    new Column('name', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'email'
                    ]),
                    new Column('auth_token', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 32,
                        'after' => 'name'
                    ]),
                    new Column('password', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'auth_token'
                    ]),
                    new Column('facebook_id', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 20,
                        'after' => 'password'
                    ]),
                    new Column('facebook_name', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 64,
                        'after' => 'facebook_id'
                    ]),
                    new Column('facebook_data', [
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'facebook_name'
                    ]),
                    new Column('gplus_id', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 20,
                        'after' => 'facebook_data'
                    ]),
                    new Column('gplus_name', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 64,
                        'after' => 'gplus_id'
                    ]),
                    new Column('gplus_data', [
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'gplus_name'
                    ]),
                    new Column('reset_token', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'gplus_data'
                    ]),
                    new Column('role_id', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '2',
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'reset_token'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'role_id'
                    ]),
                    new Column('created_at', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'status'
                    ]),
                    new Column('visited_at', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'created_at'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('UNIQUE', ['username', 'email', 'facebook_id', 'gplus_id'], 'UNIQUE')
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );
    }
}
