<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Core\Content\Ingorance;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\System\Country\CountryDefinition;

class IngoranceDefinition extends EntityDefinition
{
    public function getEntityName(): string
    {
        return 'ingos_ingorance';
        // vgl. DI tag, einheitlich mit jenem, mit Table name, mit services.xml
    }

    public function getCollectionClass(): string
    {
        return IngoranceCollection::class;
    }

    public function getEntityClass(): string
    {
        return IngoranceEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        /*
         * StringField street
         * StringField post_code
         * StringField city
         * StringField url
         * StringField telephone
         * StringField open_times
         * FkField country_id
         * ManyToOneAssociation country to CountryDefinition
         *
         * required: name street post_code city
         */
        return new FieldCollection(
            [
                (new IdField('id', 'id'))
                    ->addFlags(new Required(), new PrimaryKey()),
                new BoolField('active', 'active'),
                (new StringField('name','name'))->addFlags(new Required()),
                (new StringField('street','street'))->addFlags(new Required()),
                (new StringField('post_code','postCode'))->addFlags(new Required()),
                (new StringField('city','city'))->addFlags(new Required()),
                new StringField('url','url'),
                new StringField('telephone','telephone'),
                new StringField('open_times','openTimes'),
                new FkField('country_id','countryId', CountryDefinition::class),
                new ManyToOneAssociationField(
                    'country',
                    'country_id',
                    CountryDefinition::class,
                    'id',
                    false
                )
            ]
        );
    }
}
