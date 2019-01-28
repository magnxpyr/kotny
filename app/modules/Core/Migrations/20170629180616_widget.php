<?php 
/**
 * @copyright   2006 - 2019 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Engine\Package\Migration;
use Phalcon\Db\Column;
use Phalcon\Db\Index;

class Widget20170629180616Migration extends Migration
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
            [6, 'Top Menu', 0, 'menu', null, 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_menu":"3"}', 0, 1],
            [9, 'Top Menu Search', 1, 'menu', null, 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, null, 0, 1],
            [3, 'Homepage Carousel', 0, 'homepage-header', null, 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_images":["\/media\/ordinateur-apple-ipad-et-imac.jpg"],"_text":["<h1><span style=\"color: #f2f2f2;\">Free &amp; Open Source<\/span><br \/><span style=\"color: #f2f2f2;\">content management system<\/span><\/h1>\r\n<p><span style=\"color: #ffffff;\"><strong>PHP\/MySQL CMS and Framework<\/strong> based on <strong>fastest<\/strong> PHP Framework<\/span><\/p>\r\n<p>&nbsp;<\/p>\r\n<p><a class=\"btn btn-primary btn-large\" href=\"https:\/\/github.com\/magnxpyr\/kotny\">Github<\/a><\/p>"]}', 0, 1],
            [8, 'Latest Posts', 0, 'homepage-content-bottom', 'widget', 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_featured":"1","_category":"","_limit":"3"}', 1, 1],
            [4, 'Powered by', 0, 'footer-left', null, 'index', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_content":"Powered by <a href=\"http:\/\/www.kotny.com\">Kotny<\/a> &amp;copy 2019"}', 1, 1],
            [6, 'Footer right menu', 1, 'footer-right', NULL, 'links-inline', time(), 1, \Engine\Widget\Widget::CACHE_LIFETIME, '{"_menu":"4"}', 0, 1],
        ], ['package_id', 'title', 'ordering', 'position', 'layout', 'view', 'publish_up', 'view_level', 'cache', 'params', 'show_title', 'status']);
    }
}
