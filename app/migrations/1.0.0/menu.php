<?php 
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Tools\Builder\Mvc\Model\Migration;

class MenuMigration_100 extends Migration
{
    public function up()
    {
        $this->morphTable(
            'menu',
            [
                'columns' => [
                    new Column('id', [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'unsigned' => true,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'first' => true
                    ]),
                    new Column('menu_type_id', [
                            'type' => Column::TYPE_INTEGER,
                            'size' => 11,
                            'unsigned' => true,
                            'notNull' => true,
                            'after' => 'id'
                    ]),
                    new Column('type', [ //module,link
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 24,
                        'after' => 'menu_type_id'
                    ]),
                    new Column('title', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'type'
                    ]),
                    new Column('path', [ //module/controller/action
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'title'
                    ]),
                    new Column('link', [ //url
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'path'
                    ]),
                    new Column('status', [ //0-inactive,1-active,2-trashed
                        'type' => Column::TYPE_INTEGER,
                        'size' => 1,
                        'notNull' => true,
                        'after' => 'link'
                    ]),
                    new Column('parent_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 5,
                        'notNull' => true,
                        'after' => 'status'
                    ]),
                    new Column('level', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 2,
                        'notNull' => true,
                        'after' => 'parent_id'
                    ]),
                    new Column('lft', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 5,
                        'notNull' => true,
                        'after' => 'level'
                    ]),
                    new Column('rgt', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 5,
                        'notNull' => true,
                        'after' => 'lft'
                    ]),
                    new Column('role_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 2,
                        'notNull' => true,
                        'after' => 'rgt'
                    ]),
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('INDEX', ['menu_type_id', 'role_id', 'lft', 'rgt', 'level', 'parent_id'])
                ],
                'options' => [
                    'TABLE_TYPE' => 'BASE TABLE',
                    'AUTO_INCREMENT' => '',
                    'ENGINE' => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ]
            ]
        );
    }
}
