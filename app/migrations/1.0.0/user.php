<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Tools\Builder\Mvc\Model\Migration;

class UserMigration_100 extends Migration
{
    public function up()
    {
        $this->morphTable('user', [
            'columns' => [
                new Column('id', [
                    'type' => Column::TYPE_INTEGER,
                    'size' => 20,
                    'unsigned' => true,
                    'notNull' => true,
                    'autoIncrement' => true,
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
                new Column('auth_token', [
                    'type' => Column::TYPE_VARCHAR,
                    'size' => 32,
                    'after' => 'email'
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
                    'after' => 'gplus_name'
                ]),
                new Column('reset_token', [
                    'type' => Column::TYPE_VARCHAR,
                    'notNull' => false,
                    'size' => 255,
                    'after' => 'gplus_data'
                ]),
                new Column('role_id', [ // 1-Guest, 2-User, 3-Admin
                    'type' => 'TINYINT',
                    'notNull' => true,
                    'size' => 2,
                    'default' => 1,
                    'after' => 'reset_token'
                ]),
                new Column('status', [ // 0-Inactive, 1-Active, 2-Suspended, 3-Blocked
                    'type' => 'TINYINT',
                    'notNull' => true,
                    'size' => 2,
                    'default' => 0,
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
                ]),
            ],
            'indexes' => [
                new Index('PRIMARY', ['id']),
                new Index('UNIQUE', [ 'username', 'email', 'facebook_id', 'gplus_id'], 'UNIQUE')
            ],
            'options' => [
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            ]
        ]);
    }
}
