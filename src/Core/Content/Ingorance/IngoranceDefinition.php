<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Core\Content\Ingorance;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;

class CustomEntityDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        // TODO
    }

    public function getCollectionClass(): string
    {
        // TODO
    }

    public function getEntityClass(): string
    {
        // TODO
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new BoolField('active', 'active')),
            (new StringField('name', 'name'))
            /*
             * StringField street
             * StringField post_code
             * StringField city
             * StringField url
             * StringField telephone
             * StringField open_times
             * FkField country_i
             * ManyToOneAssociation country to CountryDefinition
             *
             * required: name street post_code city
             */
        ]);
    }
}
