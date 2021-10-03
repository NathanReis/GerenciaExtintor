<?php

namespace App\Models;

use App\Models\Location;
use App\Models\Model;

class Extinguisher extends Model
{
    /**
     * Inicia as variáveis.
     *
     * Preenche o ID que está na classe pai e utiliza a promoção de construtor
     * para definir as propriedades do extintor.
     *
     * @param int $id O ID do extintor.
     * @param App\Models\Location $location O local do extintor.
     * @param \DateTime|null $validate A data de validade do extintor ou nulo.
     */
    public function __construct(
        int $id,
        private Location $location,
        private ?\DateTime $validate
    ) {
        $this->id = $id;
    }

    /** @return App\Models\Location O local do extintor. */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param App\Models\Location $location O local do extintor.
     *
     * @return App\Models\Extinguisher O próprio extintor.
     */
    public function setLocation(Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    /** @return \DateTime|null A data de validade do extintor ou nulo. */
    public function getValidate(): ?\DateTime
    {
        return $this->validate;
    }

    /**
     * @param \DateTime|null $validate A data de validade do extintor ou nulo.
     *
     * @return App\Models\Extinguisher O próprio extintor.
     */
    public function setValidate(?\DateTime $validate): self
    {
        $this->validate = $validate;

        return $this;
    }

    /**
     * Cria um array associativo com todas propriedades do extintor.
     *
     * @return array As propriedades do extintor.
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                "location" => $this->location->toArray(),
                "validate" => $this->validate->format(\DateTime::ISO8601)
            ]
        );
    }
}
