<?php 
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Engine\Package\Migration;
use Phalcon\Db\Column;
use Phalcon\Db\Index;

class UserResetPasswordsMigration extends Migration
{
    public function up()
    {
        $this->morphTable(
            'user_reset_passwords',
            array(
                'columns' => array(
                    new Column('id', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size' => 11,
                            'first' => true
                        )
                    ),
                    new Column('user_id', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'id'
                        )
                    ),
                    new Column('token', array(
                            'type' => Column::TYPE_CHAR,
                            'notNull' => true,
                            'size' => 64,
                            'after' => 'user_id'
                        )
                    ),
                    new Column('expires', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'token'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id')),
                    new Index('UNIQUE', array('token'))
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                )
            )
        );
    }
}
