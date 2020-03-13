<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Factory;

use Kryst3q\PhpUldk\Client\HttpResponse;
use Kryst3q\PhpUldk\Client\Response;
use Kryst3q\PhpUldk\Domain\Query;
use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Exception\UldkRequestException;
use Kryst3q\PhpUldk\Model\UldkObjectCollection;
use Kryst3q\PhpUldk\Normalizer\UldkObjectNormalizer;

class ResponseFactory
{
    private UldkObjectNormalizer $uldkObjectNormalizer;

    public function __construct(UldkObjectNormalizer $uldkObjectNormalizer)
    {
        $this->uldkObjectNormalizer = $uldkObjectNormalizer;
    }

    public function create(string $requestResult, Query $query): HttpResponse
    {
        $requestResult = explode("\n", $requestResult);
        $requestResult = array_filter($requestResult, fn($value) => $value !== '');
        $status = array_shift($requestResult);

        if (strpos($status, '-') === 0) {
            throw new UldkRequestException(substr($status, 3));
        }

        $uldkObjectCollection = new UldkObjectCollection();

        if ($query->getElement(RequestName::ELEMENT_KEY)->getValue() === RequestName::SNAP_TO_POINT) {
            $uldkObject = $this->uldkObjectNormalizer->denormalize($requestResult[0], $query);
            $uldkObject->setDistanceToSnappedPointInMeters((float) $requestResult[1]);

            $uldkObjectCollection->add($uldkObject);
        } else {
            foreach ($requestResult as $objectData) {
                $uldkObjectCollection->add($this->uldkObjectNormalizer->denormalize($objectData, $query));
            }
        }

        return new Response($uldkObjectCollection, $query);
    }
}
