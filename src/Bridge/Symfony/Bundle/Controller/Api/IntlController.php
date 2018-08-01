<?php

declare(strict_types=1);

namespace Damax\User\Bridge\Symfony\Bundle\Controller\Api;

use Damax\Common\Bridge\Symfony\Bundle\Annotation\Serialize;
use Damax\User\Application\Dto\LocaleDto;
use Damax\User\Application\Service\IntlService;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as OpenApi;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/locales", methods={"GET"})
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
     * @Route("/timezones", methods={"GET"})
     * @Serialize()
     */
    public function timezonesAction(): array
    {
        return $this->service->fetchTimezones();
    }
}
