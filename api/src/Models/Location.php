<?php

namespace App\Models;

use App\Models\Extinguisher;
use App\Models\Model;

class Location extends Model
{
    /**
     * Inicia as variáveis.
     *
     * Preenche o ID que está na classe pai e utiliza a promoção de construtor
     * para definir as propriedades do extintor.
     *
     * @param int $id O ID do local.
     * @param string $description A descrição do local.
     * @param App\Models\Extinguisher[] $extinguishers Os extintores no local.
     */
    public function __construct(
        int $id,
        private string $description = "",
        private array $extinguishers = []
    ) {
        $this->id = $id;
    }

    /** @return string A descrição do local. */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description A descrição do local.
     *
     * @return App\Models\Location O próprio local.
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /** @return App\Models\Extinguisher[] Os extintores no local. */
    public function getExtinguishers(): array
    {
        return $this->extinguishers;
    }

    /**
     * @param App\Models\Extinguisher[] $extinguishers Os extintores no local.
     *
     * @return App\Models\Location O próprio local.
     */
    public function setExtinguishers(array $extinguishers): self
    {
        $this->extinguishers = $extinguishers;

        return $this;
    }

    /**
     * Cria um array associativo com todas propriedades do local.
     *
     * @return array As propriedades do local.
     */
    public function toArray(): array
    {
        return array_merge(
            parent::toArray(),
            [
                "description" => $this->description,
                "extinguishers" => array_map(
                    function ($extinguisher) {
                        $extinguisherAsArray = $extinguisher->toArray();
                        unset($extinguisherAsArray["location"]);

                        return $extinguisherAsArray;
                    },
                    $this->extinguishers
                )
            ]
        );
    }
}
