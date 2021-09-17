<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Core\Content\Ingorance;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void             add(IngoranceCollection $entity)
 * @method void             set(string $key, IngoranceCollection $entity)
 * @method IngoranceCollection[]   getIterator()
 * @method IngoranceCollection[]   getElements()
 * @method IngoranceCollection|null get(string $key)
 * @method IngoranceCollection|null first()]
 * @method IngoranceCollection|null last()
 */

class IngoranceCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return IngoranceEntity::class;
    }
}
