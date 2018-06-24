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

class Alias20180515185610Migration extends Migration
{
    const TABLE_NAME = 'alias';

    public function up()
    {
        $this->morphTable(
            self::TABLE_NAME,
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
                    new Column('url', [
                        'type' => Column::TYPE_VARCHAR,
                        'notNull' => true,
                        'size' => 255,
                        'after' => 'id'
                    ]),
                    new Column('params', [
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 255,
                        'after' => 'url'
                    ]),
                    new Column('route_id', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 11,
                        'unsigned' => true,
                        'after' => 'params'
                    ]),
                    new Column('status', [
                        'type' => Column::TYPE_INTEGER,
                        'size' => 1,
                        'after' => 'route_id'
                    ])
                ],
                'indexes' => [
                    new Index('PRIMARY', ['id']),
                    new Index('ALIAS_URL_IDX', ['url'], 'UNIQUE'),
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
