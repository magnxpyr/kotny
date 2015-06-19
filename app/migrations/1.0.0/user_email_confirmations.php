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

class UserEmailConfirmationsMigration_101 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'user_email_confirmations',
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
                    new Column('user_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'notNull' => true,
                        'after' => 'id'
                    ]),
                    new Column('token', [
                        'type' => Column::TYPE_CHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'user_id'
                    ]),
                    new Column('expires', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'notNull' => true,
                        'after' => 'token'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('UNIQUE', ['token'])
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );
    }
}
