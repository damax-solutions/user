<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\LocaleDto;
use Damax\User\Application\Service\IntlService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Swagger\Annotations as OpenApi;

/**
 * @Route("/intl")
 */
class IntlController
{
    private $service;

    public function __construct(IntlService $service)
    {
        $this->service = $service;
    }

    /**
     * @OpenApi\Get(
     *     tags={"user-intl"},
     *     summary="List locales.",
     *     @OpenApi\Response(
     *         response=200,
     *         description="Locales list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(ref=@Model(type=LocaleDto::class)))
     *     )
     * )
     *
     * @Method("GET")
     * @Route("/locales")
     * @Serialize()
     */
    public function localesAction(): array
    {
        return $this->service->fetchLocales();
    }

    /**
     * @OpenApi\Get(
     *     tags={"user-intl"},
     *     summary="List timezones.",
     *     @OpenApi\Response(
     *         response=200,
     *         description="Timezones list.",
     *         @OpenApi\Schema(type="array", @OpenApi\Items(type="string"))
     *     )
     * )
     *
     * @Method("GET")
     * @Route("/timezones")
     * @Serialize()
     */
    public function timezonesAction(): array
    {
        return $this->service->fetchTimezones();
    }
}
