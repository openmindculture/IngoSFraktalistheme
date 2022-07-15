<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Storefront\Subscriber;

use IngoSFraktalistheme\Core\Content\Ingorance\IngoranceCollection;
use PhpParser\Node\Expr\Cast\Bool_;
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
        /** @var Boolean */
        $showInStorefront = $this->systemConfigService->get('IngosFraktalistheme.config.showInStorefront');
        /** @var String */
        $showInStorefrontToString = $showInStorefront ? 'true' : 'false';
        $this->logger->info($showInStorefrontToString);
        /** @var String */
        $systemConfigServiceClass = get_class($this->systemConfigService);
        $this->logger->info("get class of this systemConfigService: $systemConfigServiceClass _^_ _^_ _^_");
        // ^ has no class ?!
        if ($this->systemConfigService->get('IngosFraktalistheme.config.showInStorefront'))
        {
            $this->logger->info('...showInStorefront is true');
        }
        else
        {
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
        // but it still has no content?! probably still missing code TODO
        // EntityRepository seems to be defined completely
        // but where do we populate it with actual demo data?
        // is there a migration for database content in the code? is there anything in the database tables?
        // Migration*.php only sets the structure but no data,
        // where is adminer to inspect the database? localhost:8001

        // which extension name is correct and why?
        // should be ingos_ingorance because n:m relation
        // of entity/extension to theme/plugin
        if ($shops) {
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
