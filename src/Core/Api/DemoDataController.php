<?php declare(strict_types=1);

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Shopware\Core\System\Country\CountryEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

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

    public function generate(): Response
    {

    }

    private function getActiveCountry(): CountryEntity
    {

    }
}
