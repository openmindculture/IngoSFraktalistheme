<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1632157456 extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1632157456;
    }

    public function update(Connection $connection): void
    {
        $tablename = 'ingos_ingorance';
        $connection->executeStatement("CREATE TABLE IF NOT EXISTS `$tablename` (
                `id` BINARY(16) NOT NULL,
                `active`        TINYINT(1) NULL DEFAULT '0',
                `name`          VARCHAR(255) NOT NULL,
                `street`        VARCHAR(255) NOT NULL,
                `post_code`     VARCHAR(255) NOT NULL,
                `city`          VARCHAR(255) NOT NULL,
                `url`           VARCHAR(255) NULL,
                `telephone`     VARCHAR(255) NULL,
                `open_times`    LONGTEXT NULL,
                `country_id`    BINARY(16) NULL,
                `created_at`    DATETIME(3),
                `updated_at`    DATETIME(3),
                PRIMARY KEY (`id`),
                KEY `fk.$tablename.country_id` (`country_id`),
                CONSTRAINT `fk.$tablename.country_id` FOREIGN KEY (`country_id`)
                    REFERENCES `country` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
