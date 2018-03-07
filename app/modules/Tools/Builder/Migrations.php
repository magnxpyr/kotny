<?php
/*
  +------------------------------------------------------------------------+
  | Phalcon Developer Tools                                                |
  +------------------------------------------------------------------------+
  | Copyright (c) 2011-2015 Phalcon Team (http://www.phalconphp.com)       |
  +------------------------------------------------------------------------+
  | This source file is subject to the New BSD License that is bundled     |
  | with this package in the file docs/LICENSE.txt.                        |
  |                                                                        |
  | If you did not receive a copy of the license and are unable to         |
  | obtain it through the world-wide-web, please send an email             |
  | to license@phalconphp.com so we can send you a copy immediately.       |
  +------------------------------------------------------------------------+
  | Authors: Andres Gutierrez <andres@phalconphp.com>                      |
  |          Eduar Carvajal <eduar@phalconphp.com>                         |
  |          Serghei Iakovlev <sadhooklay@gmail.com>                       |
  +------------------------------------------------------------------------+
*/

namespace Module\Tools\Builder;

use Module\Tools\Builder\Version\Item as VersionItem;
use Module\Tools\Builder\Mvc\Model\Migration as ModelMigration;
use Module\Tools\Builder\Component;
use Module\Tools\Helpers\Tools;

/**
 * Migrations Class
 *
 * @package     Tools
 * @copyright   Copyright (c) 2011-2015 Phalcon Team (team@phalconphp.com)
 * @license     New BSD License
 */
class Migrations
{
    /**
     * Generate migrations
     *
     * @param array $options
     * @throws \Exception
     */
    public static function generate(array $options)
    {
        $tableName = $options['tableName'];
        $exportData = $options['exportData'];
        $migrationsDir = $options['migrationsDir'];
        $config = $options['config'];

        if (empty($migrationsDir)) {
            throw new \Exception("Migration dir can't be empty");
        }

        if ($migrationsDir && !file_exists($migrationsDir)) {
            mkdir($migrationsDir, 0777, true);
        }

        $versions = array();
        $iterator = new \DirectoryIterator($migrationsDir);
        foreach ($iterator as $fileInfo) {
            if ($fileInfo->isDir()) {
                if (preg_match('/[a-z0-9](\.[a-z0-9]+)+/', $fileInfo->getFilename(), $matches)) {
                    $versions[] = new VersionItem($matches[0], 3);
                }
            }
        }

        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        $version = $date->format('YmdHis');

        ModelMigration::setup($config);

        ModelMigration::setMigrationPath($migrationsDir);
        if ($tableName == 'all') {
            $migrations = ModelMigration::generateAll($version, $exportData);
            foreach ($migrations as $tableName => $migration) {
                file_put_contents($migrationsDir . '/' . $version . '_' . $tableName . '.php', '<?php' . PHP_EOL . Tools::getCopyright() . PHP_EOL . PHP_EOL . $migration);
                @chmod($migrationsDir . '/' . $version . '_' . $tableName . '.php', 0777);
            }
        } else {
            $migration = ModelMigration::generate($version, $tableName, $exportData);
            file_put_contents($migrationsDir . '/' . $version . '_' . $tableName . '.php', '<?php ' . PHP_EOL . Tools::getCopyright() . PHP_EOL . PHP_EOL . $migration);
            @chmod($migrationsDir . '/' . $version . '_' . $tableName . '.php', 0777);
        }
    }
}

