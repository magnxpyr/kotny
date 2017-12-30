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

class MenuMigration extends Migration
{
    const TABLE_NAME = 'menu';

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
                    new Column('menu_type_id', [
                        'type' => Column::TYPE_INTEGER,
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 11,
                        'after' => 'id'
                    ]),
                    new Column('prepend', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'menu_type_id'
                    ]),
                    new Column('title', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'prepend'
                    ]),
                    new Column('path', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'title'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '1',
                        'notNull' => true,
                        'size' => 1,
                        'after' => 'path'
                    ]),
                    new Column('parent_id', [
                        'type' => Column::TYPE_INTEGER,
                        'default' => '0',
                        'unsigned' => true,
                        'notNull' => true,
                        'size' => 5,
                        'after' => 'status'
                    ]),
                    new Column('level', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 2,
                        'after' => 'parent_id'
                    ]),
                    new Column('lft', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 5,
                        'after' => 'level'
                    ]),
                    new Column('rgt', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 5,
                        'after' => 'lft'
                    ]),
                    new Column('view_level', [
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'size' => 2,
                        'after' => 'rgt'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id'])
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
            [1, 'fa fa-dashboard', 'Dashboard', 'admin', 1, 0, 1, 2, 3, 4],
            [1, 'fa fa-group', 'Users & Roles', '#', 1, 0, 1, 12, 19, 4],
            [1, 'fa fa-user', 'Users', 'admin/core/user/index', 1, 2, 2, 13, 14, 4],
            [1, 'fa fa-th-list', 'Menus', '#', 1, 0, 1, 20, 25, 4],
            [1, 'fa fa-circle-o', 'Manage', 'admin/core/menu-type/index', 1, 4, 2, 21, 22, 4],
            [1, 'fa fa-circle-o', 'Menu Items', 'admin/core/menu/index', 1, 4, 2, 23, 24, 4],
            [1, 'fa fa-list-alt', 'Web Tools', '#', 1, 0, 1, 26, 37, 4],
            [1, 'fa fa-circle-o', 'Modules', 'admin/tools/modules/index', 1, 7, 2, 27, 28, 4],
            [1, 'fa fa-circle-o', 'Controllers', 'admin/tools/controllers/index', 1, 7, 2, 29, 30, 4],
            [1, 'fa fa-circle-o', 'Models', 'admin/tools/models/index', 1, 7, 2, 31, 32, 4],
            [1, 'fa fa-circle-o', 'Migrations', 'admin/tools/migrations/index', 1, 7, 2, 33, 34, 4],
            [1, 'fa fa-circle-o', 'Scaffold', 'admin/tools/scaffold/index', 1, 7, 2, 35, 36, 4],
            [1, 'fa fa-bullseye', 'Performance', '/admin/core/cache/index', 1, 0, 1, 46, 47, 4],
            [1, 'fa fa-th-large', 'Content', '#', 1, 0, 1, 4, 11, 4],
            [1, 'fa fa-file-text-o', 'Articles', 'admin/core/content/index', 1, 25, 2, 5, 6, 4],
            [1, 'fa fa-circle-o', 'Roles', 'admin/core/role/index', 1, 2, 2, 15, 16, 4],
            [1, 'fa fa-circle-o', 'View Levels', 'admin/core/view-level/index', 1, 2, 2, 17, 18, 4],
            [1, 'fa fa-circle-o', 'Categories', 'admin/core/category/index', 1, 25, 2, 7, 8, 4],
            [1, 'fa fa-th', 'Extensions', '#', 1, 0, 1, 38, 45, 4],
            [1, 'fa fa-circle-o', 'Widgets', 'admin/core/widget/index', 1, 30, 2, 43, 44, 4],
            [1, 'fa fa-folder', 'Media', 'admin/core/file-manager/index', 1, 25, 2, 9, 10, 4],
            [1, 'fa fa-circle-o', 'Manager', 'admin/core/package-manager/index', 1, 30, 2, 39, 40, 4],
            [2, '', 'Home', '/', 1, 0, 1, 2, 3, 1],
            [2, '', 'Login', 'user/login', 1, 0, 1, 6, 7, 2],
            [2, '', 'Logout', 'user/logout', 1, 0, 1, 8, 9, 3],
            [2, '', 'Admin', 'admin', 1, 0, 1, 4, 5, 4],
        ], ['menu_type_id', 'prepend', 'title', 'path', 'status', 'parent_id', 'level', 'lft', 'rgt', 'view_level']);
    }
}
