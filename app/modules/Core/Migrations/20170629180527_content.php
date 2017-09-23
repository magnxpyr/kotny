<?php 
/**
 * @copyright   2006 - 2017 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Engine\Package\Migration;

class ContentMigration extends Migration
{
    public function up()
    {
        $this->morphTable(
            'content',
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
                            'size' => 255,
                            'after' => 'title'
                        )
                    ),
                    new Column('introtext', array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'alias'
                        )
                    ),
                    new Column('fulltext', array(
                            'type' => Column::TYPE_TEXT,
                            'size' => 1,
                            'after' => 'introtext'
                        )
                    ),
                    new Column('metadata', array(
                            'type' => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size' => 2048,
                            'after' => 'fulltext'
                        )
                    ),
                    new Column('category', array(
                            'type' => Column::TYPE_INTEGER,
                            'unsigned' => true,
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'metadata'
                        )
                    ),
                    new Column('hits', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 11,
                            'after' => 'category'
                        )
                    ),
                    new Column('featured', array(
                            'type' => Column::TYPE_INTEGER,
                            'default' => '0',
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'hits'
                        )
                    ),
                    new Column('status', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 1,
                            'after' => 'featured'
                        )
                    ),
                    new Column('view_level', array(
                            'type' => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'size' => 4,
                            'after' => 'status'
                        )
                    ),
                    new Column('publish_up', array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'view_level'
                        )
                    ),
                    new Column('publish_down', array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'view_level'
                        )
                    ),
                    new Column('created_at', array(
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'after' => 'published_at'
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
                    new Index('UNIQUE', array('alias'), 'UNIQUE'),
                    new Index('createdBy', array('created_by'))
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
