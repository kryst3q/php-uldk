<?php

declare(strict_types=1);

namespace Kryst3q\PhpUldk\Model;

class UldkObject
{
    private Geometry $geometry;
    private ?BoundingBox $boundingBox;
    private ?string $identifier;
    private ?string $voivodeshipName;
    private ?string $countyName;
    private ?string $communeName;
    private ?string $regionName;
    private ?string $parcelNameOrNumber;

    public function __construct(Geometry $geometry)
    {
        $this->geometry = $geometry;
    }

    public function getGeometry(): Geometry
    {
        return $this->geometry;
    }

    public function getBoundingBox(): ?BoundingBox
    {
        return $this->boundingBox;
    }

    public function setBoundingBox(?BoundingBox $boundingBox): self
    {
        $this->boundingBox = $boundingBox;
        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): self
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function getVoivodeshipName(): ?string
    {
        return $this->voivodeshipName;
    }

    public function setVoivodeshipName(?string $voivodeshipName): self
    {
        $this->voivodeshipName = $voivodeshipName;
        return $this;
    }

    public function getCountyName(): ?string
    {
        return $this->countyName;
    }

    public function setCountyName(?string $countyName): self
    {
        $this->countyName = $countyName;
        return $this;
    }

    public function getCommuneName(): ?string
    {
        return $this->communeName;
    }

    public function setCommuneName(?string $communeName): self
    {
        $this->communeName = $communeName;
        return $this;
    }

    public function getRegionName(): ?string
    {
        return $this->regionName;
    }

    public function setRegionName(?string $regionName): self
    {
        $this->regionName = $regionName;
        return $this;
    }

    public function getParcelNameOrNumber(): ?string
    {
        return $this->parcelNameOrNumber;
    }

    public function setParcelNameOrNumber(?string $parcelNameOrNumber): self
    {
        $this->parcelNameOrNumber = $parcelNameOrNumber;
        return $this;
    }
}
