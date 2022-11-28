<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Storefront\Subscriber;

use IngoSFraktalistheme\Core\Content\Ingorance\IngoranceCollection;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class FooterSubscriber implements EventSubscriberInterface
{

    /** @var SystemConfigService */
    private SystemConfigService $systemConfigService;

    /** @var EntityRepository */
    private EntityRepository $ingoranceRepository;

    /** @var LoggerInterface */
    private LoggerInterface $logger;

    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepository $ingoranceService,
        LoggerInterface $logger
    )
    {
        $this->systemConfigService = $systemConfigService;
        $this->ingoranceRepository = $ingoranceService;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
          FooterPageletLoadedEvent::class => 'onFooterPageletLoaded'
        ];
    }

    public function onFooterPageletLoaded(FooterPageletLoadedEvent $event): void
    {
        $this->logger->info("_^_ _^_ _^_ check systemConfigService get IngosFraktalistheme.config.showInStorefront ");
        /** @var Boolean $showInStorefront */
        $showInStorefront = $this->systemConfigService->get('IngosFraktalistheme.config.showInStorefront');
        /** @var String $showInStorefrontToString */
        $showInStorefrontToString = $showInStorefront ? 'true' : 'false';
        $this->logger->info($showInStorefrontToString);
        /** @var String $systemConfigServiceClass */
        $systemConfigServiceClass = get_class($this->systemConfigService);
        $this->logger->info("get class of this systemConfigService: $systemConfigServiceClass _^_ _^_ _^_");
        // ^ has no class ?!
        if ($this->systemConfigService->get('IngosFraktalistheme.config.showInStorefront')) {
            $this->logger->info('...showInStorefront is true');
        } else {
            $this->logger->info('...showInStorefront is false');
        }
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
        // to ensure demo data after installation call FakerFactory API route on localhost

        // which extension name is correct and why?
        // should be ingos_ingorance because n:m relation
        // of entity/extension to theme/plugin

        // ExtendableTrait::addExtension(string $name, ?Struct $extension)
        // @deprecated tag:v6.5.0 - second param $extension will not allow null anymore
        // PhpStorm inspection notes: "condition is always true because $shops is evaluated at this point"
        // (and it gets autoamtically tooltipped as of type IngoSFraktalistheme\Core\Content\Ingorance\IngoranceCollection
        // so addExtension should be valid and not deprecated here, see
        // https://stackoverflow.com/questions/72968625/replacement-for-deprecated-shopware-6-addextension-method
        // the deprecated note will be removed since 6.5.0 then you can still use it.
        if ($shops) {
            // the deprecated note will be removed since 6.5.0 then you can still use it
            // https://stackoverflow.com/questions/72968625/replacement-for-deprecated-shopware-6-addextension-method
            // @deprecated tag:v6.5.0 - second param $extension will not allow null anymore as I understood that we still use that function but the second param can not be null.
            $event->getPagelet()->addExtension('ingos_fraktalistheme', $shops);
            $event->getPagelet()->addExtension('ingos_ingorance', $shops);
        }
        $this->logger->info('testlog________________________________X_X_X_X_X_X________________TEST_______________');
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
