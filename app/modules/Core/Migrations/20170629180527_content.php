<?php
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Engine\Package\Migration;

class ContentMigration extends Migration
{
    const TABLE_NAME = 'content';

    public function up()
    {
        $this->morphTable(
            'content',
            [
                'columns' => [
                    new Column('id', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 11,
                        'first' => true
                    ]),
                    new Column('title', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'id'
                    ]),
                    new Column('alias', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'title'
                    ]),
                    new Column('introtext', [
                        'type' => Column::TYPE_TEXT,
                        'after' => 'alias'
                    ]),
                    new Column('fulltext', [
                        'type' => Column::TYPE_TEXT,
                        'after' => 'introtext'
                    ]),
                    new Column('images', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'fulltext'
                    ]),
                    new Column('metadata', [
                        'type' => Column::TYPE_TEXT,
                        'after' => 'images'
                    ]),
                    new Column('category', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'metadata'
                    ]),
                    new Column('hits', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'category'
                    ]),
                    new Column('featured', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'hits'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'featured'
                    ]),
                    new Column('view_level', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'status'
                    ]),
                    new Column('publish_up', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'view_level'
                    ]),
                    new Column('publish_down', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'view_level'
                    ]),
                    new Column('created_at', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'published_at'
                    ]),
                    new Column('created_by', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'created_at'
                    ]),
                    new Column('modified_at', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'created_by'
                    ]),
                    new Column('modified_by', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'size' => 11,
                        'after' => 'modified_at'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('UNIQUE', ['alias'], 'UNIQUE'),
                    new Index('createdBy', ['created_by'])
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '1',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );
    }
}
