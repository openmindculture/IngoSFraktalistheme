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

    function __construct()
    {
        // define some demo data - TODO use the /generate action using FakerFactory instead!
        $countryIdGermany = '628FBE603DB140938F7CA9D8B029D996'; //  Doctrine should handle data conversion to BINARY
        $this->demodata = array(
            [true, 'Kleiderordnung Berlin', 'Donaustraße 83', '12043', 'Berlin', 'example.com', '123', null, $countryIdGermany],
            [true, 'HeartSpace', 'Urbanstraße 71', '10967', 'Berlin', null, null, null, $countryIdGermany],
            [true, 'Café zum übergroßen Bären', 'Neunzigstraße 6a', '40625', 'Düsseldorf', 'www.ingo-steinke.de', '0815/4711', null, $countryIdGermany],
        );
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
        $queryBuilder = $connection->createQueryBuilder();
        foreach ($this->demodata as $demoitem) {
            $queryBuilder
                ->insert($tablename)
                ->values([
                    'active' => '?', 'name' => '?', 'street' => '?', 'post_code' => '?', 'city' => '?', 'url' => '?',
                    'telephone' => '?', 'open_times' => '?', 'country_id' => '?'
                ])
                ->setParameter(0, $demoitem[0])
                ->setParameter(1, $demoitem[1])
                ->setParameter(2, $demoitem[2])
                ->setParameter(3, $demoitem[3])
                ->setParameter(4, $demoitem[4])
                ->setParameter(5, $demoitem[5])
                ->setParameter(6, $demoitem[6])
                ->setParameter(7, $demoitem[7])
                ->setParameter(8, $demoitem[8])
            ;
            // INSERT INTO $tablename (active, name) VALUES (?, ?)
        }
    }

    public function updateDestructive(Connection $connection): void
    {
    }


}
