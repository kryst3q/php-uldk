<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk;

use Kryst3q\PhpUldk\Domain\RequestName;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\Model\UldkObject;

class PhpUldk
{
    public function __construct()
    {
    }

    public function getParcelById(string $parcelId): UldkObject
    {
        $path = 'https://uldk.gugik.gov.pl';
        $rawQuery = [
            'request' => RequestName::GET_PARCEL_BY_ID,
            'id' => $parcelId,
            'result' => (string)(new ResponseContentOptions()),
            'srid' => '4326'
        ];
        $parsedQuery = http_build_query($rawQuery, '', '&');
        dump($parsedQuery);
        $url = $path.'?'.$parsedQuery;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $rawSerializedResult = curl_exec($ch);
        $rawResult = explode(';', substr($rawSerializedResult, 1));
        $bbox = str_replace("\n", '', explode('|', $rawResult[0])[0]);
        $area = $rawResult[1];

//        dd($bbox, $area);
        curl_close($ch);
    }
}
