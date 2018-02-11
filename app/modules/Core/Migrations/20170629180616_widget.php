<?php 
/**
 * @copyright   2006 - 2018 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Engine\Package\Migration;
use Phalcon\Db\Column;
use Phalcon\Db\Index;

class WidgetMigration extends Migration
{
    const TABLE_NAME = 'widget';

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
                    new Column('package_id', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'id'
                    ]),
                    new Column('title', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'package_id'
                    ]),
                    new Column('ordering', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 2,
                        'default' => 0,
                        'after' => 'title'
                    ]),
                    new Column('position', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 100,
                        'after' => 'order'
                    ]),
                    new Column('layout', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 100,
                        'after' => 'position'
                    ]),
                    new Column('view', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 100,
                        'after' => 'layout'
                    ]),
                    new Column('publish_up', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 1,
                        'after' => 'view'
                    ]),
                    new Column('publish_down', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 1,
                        'after' => 'publish_up'
                    ]),
                    new Column('view_level', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 2,
                        'after' => 'publish_down'
                    ]),
                    new Column('cache', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'after' => 'view_level'
                    ]),
                    new Column('params', [
                        'type' => Column::TYPE_TEXT,
                        'after' => 'cache'
                    ]),
                    new Column('show_title', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'default' => 1,
                        'size' => 1,
                        'after' => 'params'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'default' => 1,
                        'size' => 1,
                        'after' => 'show_title'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('position', ['position']),
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
            [5, 'Homepage Menu', 0, 'menu', null, 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_menu":"3"}', 0, 1],
            [2, 'Homepage Carousel', 0, 'header', null, 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_images":["https:\/\/farm1.staticflickr.com\/516\/32235079792_0ce7b5c93f_k.jpg"]}', 0, 1],
            [7, 'Latest Posts', 0, 'footer', 'widget', 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_category":"","_limit":"3"}', 1, 1]
        ], ['package_id', 'title', 'ordering', 'position', 'layout', 'view', 'publish_up', 'view_level', 'cache', 'params', 'show_title', 'status']);
    }
}
