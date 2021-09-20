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
        // implement update
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
