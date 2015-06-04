<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Tools\Builder\Mvc\Model\Migration;

class UserMigration_100 extends Migration {

    public function up() {
        $this->morphTable('user',
            array(
                'columns' => array(
                    new Column('id', array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'first' => true
                        )
                    ),
                    new Column('username', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 50,
                            'after' => 'id'
                        )
                    ),
                    new Column('auth_token', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 32,
                            'after' => 'username'
                        )
                    ),
                    new Column('password', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'salt_key'
                        )
                    ),
                    new Column('reset_token', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => false,
                            'size' => 255,
                            'after' => 'password'
                        )
                    ),
                    new Column('email', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'reset_token'
                        )
                    ),
                    new Column('role', array( // Guest, User, Admin
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 6,
                            'after' => 'email'
                        )
                    ),
                    new Column('status', array( // 0-Inactive, 1-Active, 2-Blocked
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 6,
                    //        'default' => '0',
                            'after' => 'role'
                        )
                    ),
                    new Column('created_at', array(
                            'type' => 'TIMESTAMP',
                   //         'notNull' => true,
                   //         'default' => 'CURRENT_TIMESTAMP',
                            'after' => 'status'
                        )
                    ),
                    new Column('visited_at', array(
                            'type' => 'TIMESTAMP',
                            'after' => 'created_at'
                        )
                    ),
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id')),
                    new Index('UNIQUE', array( 'username', 'email'), 'UNIQUE')
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '0',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                )
            )
        );
    }
}
