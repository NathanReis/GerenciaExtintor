<?php

namespace App\Models;

abstract class Model
{
    /** @var int O ID do modelo. */
    protected int $id;

    /** @return int O ID do modelo. */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id O ID do modelo.
     *
     * @return App\Models\Model O próprio modelo.
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Cria um array associativo com todas propriedades do modelo.
     *
     * @return array As propriedades do modelo.
     */
    public function toArray(): array
    {
        return ["id" => $this->id];
    }
}
