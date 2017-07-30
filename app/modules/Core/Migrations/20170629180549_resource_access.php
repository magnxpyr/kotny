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

class ResourceAccessMigration extends Migration
{
    public function up()
    {
        $this->morphTable(
            'resource_access',
            array(
                'columns' => array(
                    new Column('resource_id', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'first' => true
                        )
                    ),
                    new Column('access_name', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 64,
                            'after' => 'resource_id'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('resource_id', 'access_name'))
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                )
            )
        );
    }
}
