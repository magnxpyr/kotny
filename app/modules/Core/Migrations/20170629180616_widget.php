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

class WidgetMigration extends Migration
{
    public function up()
    {
        $this->morphTable(
            'widget',
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
                    new Column('name', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        )
                    ),
                    new Column('params', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'name'
                        )
                    ),
                    new Column('description', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'params'
                        )
                    ),
                    new Column('version', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 20,
                            'after' => 'description'
                        )
                    ),
                    new Column('author', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'version'
                        )
                    ),
                    new Column('website', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'author'
                        )
                    ),
                    new Column('status', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'website'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id')),
                    new Index('UNIQUE', array('name')),
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '5',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                )
            )
        );
    }
}
