# php-uldk

Object oriented library written in php for obtaining polish cadastral plot geographic localisation using it's number or coordinates. The library uses ULDK www service for obtaining informations (http://uldk.gugik.gov.pl/opis.html).

## Installation

```shell script
composer require kryst3q/php-uldk
```

## Usage

Assuming that php file lies in project's root directory on the same level as vendor directory:

```php
<?php

use Kryst3q\PhpUldk\Domain\CoordinateSystem;
use Kryst3q\PhpUldk\Domain\ObjectIdentifier;
use Kryst3q\PhpUldk\Domain\ResponseContentOptions;
use Kryst3q\PhpUldk\PhpUldk;
use Kryst3q\PhpUldk\ValueObject\GeometryFormat;

require_once __DIR__.'/vendor/autoload.php';

# 1. Create an instance of PhpUldk class.
$uldk = new PhpUldk();

# 2. Prepare input arguments. In below case it will be parcel identifier.
$parcelId = new ObjectIdentifier('141201_1.0001.6509');

# 3. (optional) Prepare options. It depends on them what information will be returned.
$options = new ResponseContentOptions();
$options->setGeometryFormat(new GeometryFormat(GeometryFormat::FORMAT_WKT)); # Default is WKB
$options->setCoordinateSystem(new CoordinateSystem(CoordinateSystem::SRID_4326)); # Default is ESD:2180
$options->requestBoundaryBox();
#$options->requestParcelNumber();
#$options->requestCommuneName();
#$options->requestRegionNameOrNumber();
#$options->requestCountyName();
#$options->requestVoivodeshipName();
#$options->requestObjectIdentifier();

# 4. Make request.
$uldkObject = $uldk->getParcelById($parcelId, $options);

# 5. Read data from UldkObject or UldkObjectCollection classes.
echo $uldkObject->getGeometry()->getGeometry();
echo "\n";
echo $uldkObject->getBoundingBox()->getValue();
echo "\n";
```

## Important

* Up to know only two coordinates systems are supported: EPSG:2180 and EPSG:4326. If you need another you must extend Kryst3q\PhpUldk\Domain\CoordinateSystem::getSupportedSrids method by yours SRID.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## Licence

[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)
