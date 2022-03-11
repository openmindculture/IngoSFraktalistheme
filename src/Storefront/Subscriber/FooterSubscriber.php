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
        error_log('IngosFraktalistheme: FooterSubscriber: onFooterPageletLoaded (via error_log)');
        // Shopware()->Container()->get('pluginlogger')->info('IngosFraktalistheme: FooterSubscriber: onFooterPageletLoaded (via pluginlogger)');
        if(!$this->systemConfigService->get('IngosFraktalistheme.config.showInStorefront')) {
            error_log('IngosFraktalistheme: FooterSubscriber: onFooterPageletLoaded: not configured to showInStorefront');
            return;
        }

        $shops = $this->fetchShops($event->getContext());

        $event->getPagelet()->addExtension('ingos_fraktalistheme', $shops);
        $event->getPagelet()->addExtension('ingos_ingorance', $shops);
        error_log('*** error_log test von FooterSubscriber: onFooterPageletLoaded ***');
        error_log('*** custom dest /var/log/custom.log', 3, '/var/log/custom.log');
        error_log('*** custom dest var/log/custom.log', 3, 'var/log/custom.log');
        /*
         * ABER hier addExtension ingos_fraktalistheme
         * doch dort test auf     ingos_ingorance
         *
         * Sind aktuell aber beide leer.
         * Kommt der Code überhaupt hier hin? (jetzt wäre logging doch hilfreich)
         * Könnten wir hier alternativ in ingos_ingorance schreiben?
         * (These: anderes hat keinen Effekt (aber auch keinen Fehler?!) weil es das Ziel nicht gibt.
         */
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
