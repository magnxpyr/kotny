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

class CategoryMigration extends Migration
{
    public function up()
    {
        $this->morphTable(
            'category',
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
                    new Column('title', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'id'
                        )
                    ),
                    new Column('alias', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 255,
                            'after' => 'title'
                        )
                    ),
                    new Column('description', array(
                            'type' => Column::TYPE_VARCHAR,
                            'size' => 255,
                            'after' => 'alias'
                        )
                    ),
                    new Column('metadata', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 2048,
                            'after' => 'description'
                        )
                    ),
                    new Column('hits', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'metadata'
                        )
                    ),
                    new Column('status', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'hits'
                        )
                    ),
                    new Column('view_level', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'status'
                        )
                    ),
                    new Column('parent_id', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 5,
                            'after' => 'view_level'
                        )
                    ),
                    new Column('level', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 2,
                            'after' => 'parent_id'
                        )
                    ),
                    new Column('lft', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 5,
                            'after' => 'level'
                        )
                    ),
                    new Column('rgt', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 5,
                            'after' => 'lft'
                        )
                    ),
                    new Column('created_at', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'rgt'
                        )
                    ),
                    new Column('created_by', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'created_at'
                        )
                    ),
                    new Column('modified_at', array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'created_by'
                        )
                    ),
                    new Column('modified_by', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'size' => 11,
                            'after' => 'modified_at'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id')),
                    new Index('UNIQUE', array('alias')),
                    new Index('INDEX', array('created_by'))
                ),
                'options' => array(
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '3',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                )
            )
        );
    }
}
