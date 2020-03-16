<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Provider;

use Kryst3q\PhpUldk\Client\Request;
use Kryst3q\PhpUldk\Factory\BoundingBoxFactory;
use Kryst3q\PhpUldk\Factory\GeometryFactory;
use Kryst3q\PhpUldk\Factory\ResponseFactory;
use Kryst3q\PhpUldk\Normalizer\UldkObjectNormalizer;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ServiceProvider
{
    public static function buildContainer(): ContainerBuilder
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->setParameter('url', 'https://uldk.gugik.gov.pl/');

        $containerBuilder
            ->register('request', Request::class)
            ->setPublic(true)
            ->addArgument('%url%')
            ->addArgument(new Reference('response.factory'));

        $containerBuilder
            ->register('response.factory', ResponseFactory::class)
            ->addArgument(new Reference('uldk_object.normalizer'));

        $containerBuilder
            ->register('uldk_object.normalizer', UldkObjectNormalizer::class)
            ->addArgument(new Reference('geometry.factory'))
            ->addArgument(new Reference('bounding_box.factory'));

        $containerBuilder
            ->register('geometry.factory', GeometryFactory::class);

        $containerBuilder
            ->register('bounding_box.factory', BoundingBoxFactory::class);

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
