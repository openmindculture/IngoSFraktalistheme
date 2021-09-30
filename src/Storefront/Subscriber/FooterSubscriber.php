<?php declare(strict_types=1);

namespace IngoSFraktalistheme\Storefront\Subscriber;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Recovery\Common\Service\SystemConfigService;
use Shopware\Storefront\Pagelet\Footer\FooterPageletLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class FooterSubscriber implements EventSubscriberInterface
{


    public function __construct(
        SystemConfigService $systemConfigService,
        EntityRepositoryInterface $ingoranceService
    )
    {
    }

    public static function getSubscribedEvents()
    {
        return [
          FooterPageletLoadedEvent::class => 'onFooterPageletLoaded'
        ];
    }

    public function onFooterPageletLoaded(FooterPageletLoadedEvent $event): void
    {}
}
