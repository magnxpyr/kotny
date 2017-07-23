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

class MenuMigration extends Migration
{
    public function up()
    {
        $this->morphTable(
            'menu',
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
                    new Column('menu_type_id', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'id'
                        )
                    ),
                    new Column('prepend', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'menu_type_id'
                        )
                    ),
                    new Column('title', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'prepend'
                        )
                    ),
                    new Column('path', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'title'
                        )
                    ),
                    new Column('status', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '1',
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'path'
                        )
                    ),
                    new Column('parent_id', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 5,
                            'after' => 'status'
                        )
                    ),
                    new Column('level', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 2,
                            'after' => 'parent_id'
                        )
                    ),
                    new Column('lft', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 5,
                            'after' => 'level'
                        )
                    ),
                    new Column('rgt', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 5,
                            'after' => 'lft'
                        )
                    ),
                    new Column('view_level', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 2,
                            'after' => 'rgt'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'))
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '35',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                )
            )
        );
    }
}
