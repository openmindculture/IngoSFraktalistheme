<?php declare(strict_types=1);

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/** RouteScope
 * @RouteScope(scopes={"api"})
 */
class DemoDataController extends AbstractController {
    /**
     * @var EntityRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $ingoranceRepository;

    public function __construct(
        EntityRepositoryInterface $countryRepository,
        EntityRepositoryInterface $ingoranceRepository
    )
    {
        $this->countryRepository = $countryRepository;
        $this->ingoranceRepository = $ingoranceRepository;
    }
}
