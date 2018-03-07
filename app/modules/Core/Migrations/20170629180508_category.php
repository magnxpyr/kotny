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

class CategoryMigration extends Migration
{
    const TABLE_NAME = 'category';

    public function up()
    {
        $this->morphTable(
            self::TABLE_NAME,
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
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'title'
                    ]),
                    new Column('description', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'alias'
                    ]),
                    new Column('metadata', [
                        'type' => Column::TYPE_TEXT,
                        'after' => 'fulltext'
                    ]),
                    new Column('hits', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'metadata'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'hits'
                    ]),
                    new Column('view_level', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 4,
                        'after' => 'status'
                    ]),
                    new Column('parent_id', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 5,
                        'after' => 'view_level'
                    ]),
                    new Column('level', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'notNull' => true,
                        'size' => 2,
                        'after' => 'parent_id'
                    ]),
                    new Column('lft', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'notNull' => true,
                        'size' => 5,
                        'after' => 'level'
                    ]),
                    new Column('rgt', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'notNull' => true,
                        'size' => 5,
                        'after' => 'lft'
                    ]),
                    new Column('created_at', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'rgt'
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

        $this->batchInsert(self::TABLE_NAME, [
            ['Uncategorised', 'uncategorised', '', '{"metaTitle":"","metaKeywords":"","metaDescription":""}', 0, 1, 1, 0, 1, 0, 0, time(), 1],
        ], ['title', 'alias', 'description', 'metadata', 'hits', 'status', 'view_level', 'parent_id', 'level', 'lft', 'rgt', 'created_at', 'created_by']);
    }
}
