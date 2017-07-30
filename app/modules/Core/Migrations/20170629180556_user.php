<?php 
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Engine\Package\Migration;

class UserMigration extends Migration
{
    public function up()
    {
        $this->morphTable(
            'user',
            array(
                'columns' => array(
                    new Column('id', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 20,
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
                    new Column('email', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'username'
                        )
                    ),
                    new Column('name', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'email'
                        )
                    ),
                    new Column('auth_token', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 32,
                            'after' => 'name'
                        )
                    ),
                    new Column('password', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'auth_token'
                        )
                    ),
                    new Column('facebook_id', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 20,
                            'after' => 'password'
                        )
                    ),
                    new Column('facebook_name', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 64,
                            'after' => 'facebook_id'
                        )
                    ),
                    new Column('facebook_data', array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'facebook_name'
                        )
                    ),
                    new Column('gplus_id', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 20,
                            'after' => 'facebook_data'
                        )
                    ),
                    new Column('gplus_name', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 64,
                            'after' => 'gplus_id'
                        )
                    ),
                    new Column('gplus_data', array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'gplus_name'
                        )
                    ),
                    new Column('reset_token', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'gplus_data'
                        )
                    ),
                    new Column('role_id', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '2',
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'reset_token'
                        )
                    ),
                    new Column('status', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'role_id'
                        )
                    ),
                    new Column('created_at', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'status'
                        )
                    ),
                    new Column('visited_at', array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'created_at'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id')),
                    new Index('UNIQUE', array('username', 'email', 'facebook_id', 'gplus_id'))
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '2',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                )
            )
        );
    }
}
