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

class ResourceAccessMigration_101 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'resource_access',
            [
                'columns' => [
                    new Column('resource_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'notNull' => true,
                        'first' => true
                    ]),
                    new Column('access_name', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 64,
                        'after' => 'resource_id'
                    ]),
                ],
                'indexes' => [
                    new Index('PRIMARY', ['resource_id', 'access_name'])
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
