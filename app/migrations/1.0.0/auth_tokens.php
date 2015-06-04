<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Tools\Builder\Mvc\Model\Migration;

class AuthTokensMigration_100 extends Migration {

    public function up() {
        $this->morphTable('auth_tokens', array(
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
                new Column('selector', array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 12,
                        'after' => 'id'
                    )
                ),
                new Column('token', array(
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'selector'
                    )
                ),
                new Column('user_id', array(
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'token'
                    )
                ),
                new Column('expires', array(
                        'type' => 'TIMESTAMP',
                        //'notNull' => true,
                        //'default' => 'CURRENT_TIMESTAMP',
                        'after' => 'user_id'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id')),
                new Index('UNIQUE', ['selector'], 'UNIQUE')
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '0',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            )
        ));
    }
}
