<?php 
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Engine\Package\Migration;

class Route20180517191323Migration extends Migration
{
    const TABLE_NAME = 'route';

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
                    new Column('name', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'id'
                    ]),
                    new Column('pattern', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'name'
                    ]),
                    new Column('method', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'pattern'
                    ]),
                    new Column('package_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'after' => 'method'
                    ]),
                    new Column('controller', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'package_id'
                    ]),
                    new Column('action', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'controller'
                    ]),
                    new Column('params', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'action'
                    ]),
                    new Column('ordering', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 3,
                        'after' => 'params'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'ordering'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('UNIQUE', ['name'], 'UNIQUE'),
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );

        $this->batchInsert(self::TABLE_NAME, [
            [1, 'homepage', '/', '["GET"]', 1, 'index', 'index', NULL, 1, 1],
            [2, 'article', '/articles/([a-zA-Z0-9\\-]+)', '["GET"]', 1, 'content', 'article', '{"articleAlias":"1"}', 2, 1],
            [3, 'category', '/([a-z]+)', '["GET"]', 1, 'content', 'category', '{"category":"1"}', 3, 1],
            [4, 'category-pagination', '/([a-z]+)/([0-9]+)', '["GET"]', 1, 'content', 'category', '{"category":"1","page":"2"}', 4, 1],
            [5, 'articles-pagination', '/articles/([0-9]+)', '["GET"]', 1, 'content', 'category', '{"category":"","page":"1"}', 5, 1],
            [6, 'articles', '/articles', '["GET"]', 1, 'content', 'category', NULL, 6, 1],
            [7, 'user', '/user/([a-zA-Z0-9\\-\\.]+)', NULL, 1, 'user', '1', NULL, 7, 1],
            [8, 'admin-dashboard', '/admin', '["GET"]', 1, 'admin-index', 'index', NULL, 8, 1]
        ], ['id', 'name', 'pattern', 'method', 'package_id', 'controller', 'action', 'params', 'ordering', 'status']);
    }
}
