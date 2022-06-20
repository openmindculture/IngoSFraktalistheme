<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Storefront\Subscriber;

use IngoSFraktalistheme\Core\Content\Ingorance\IngoranceCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Components\Logger;

class FooterSubscriber implements EventSubscriberInterface
{

    /** @var SystemConfigService */
    private SystemConfigService $systemConfigService;

    /** @var EntityRepositoryInterface */
    private EntityRepositoryInterface $ingoranceRepository;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $ingoranceService
    )
    {
        $this->systemConfigService = $systemConfigService;
        $this->ingoranceRepository = $ingoranceService;
    }

    public static function getSubscribedEvents()
    {
        return [
          FooterPageletLoadedEvent::class => 'onFooterPageletLoaded'
        ];
    }

    public function onFooterPageletLoaded(FooterPageletLoadedEvent $event): void
    {
        if(!$this->systemConfigService->get('IngosFraktalistheme.config.showInStorefront')) {
            // function returns here
            // but it should not if the config.showInStorefront is now a boolean and the setting is checked!
            // Where do we find the actual settings (config is not in our custom table but in a default SW table)
            // SELECT configuration_value from system_config WHERE configuration_key = "IngosFraktalistheme.config.showInStorefront"
            // All of this is correct so far, and I used the same getter syntax in another plugin in the past.
            // Coding style using negation to return early also should be current best practice as far as I know.
            // return;
            // TODO solve this config problem (later)!
        }
        // following code is not executed (unless we comment out the if above)

        $shops = $this->fetchShops($event->getContext());
        // $shops will be inserted into our extension below,
        // but it still has no content?! probably still missing code TODO
        // EntityRepository seems to be defined completely
        // but where do we populate it with actual demo data?
        // is there a migration for database content in the code? is there anything in the database tables?
        // Migration*.php only sets the structure but no data,
        // where is adminer to inspect the database? localhost:8001

        // which extension name is correct and why?
        // should be ingos_ingorance because n:m relation
        // of entity/extension to theme/plugin
        $event->getPagelet()->addExtension('ingos_fraktalistheme', $shops);
        $event->getPagelet()->addExtension('ingos_ingorance', $shops);
    }

    private function fetchShops(Context $context): IngoranceCollection
    {
        $criteria = new Criteria();
        $criteria->addAssociation('country');
        $criteria->addFilter(new EqualsFilter('active', 1));
        $criteria->setLimit(5);

        /** @var IngoranceCollection $ingoranceCollection */
        $ingoranceCollection = $this->ingoranceRepository->search($criteria, $context)->getEntities();

        return $ingoranceCollection;
    }
}
