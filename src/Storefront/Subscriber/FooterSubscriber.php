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
            return;
        }
        // following code is not executed
        $shops = $this->fetchShops($event->getContext());
        // which extension name is correct and why? both are empty when tested, as this code is never executed
        $event->getPagelet()->addExtension('ingos_fraktalistheme', $shops);
        $event->getPagelet()->addExtension('ingos_ingorance', $shops);
        $event->thisMethodDoesNotExist('onFooterPageletLoaded'); // TODO remove "debug logging"
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
