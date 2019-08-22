<?php
declare(strict_types=1);
namespace James;

use James\Bond;

class M
{
    private $equipments = [];

    public function addEquipment(Equipment\Gear $equipment): self
    {
      $this->equipments[] = $equipment;

      return $this;
    }

    public function equip(Bond $OO7): void
    {
      foreach ($this->equipments as $equipment) {
        $OO7->attach($equipment->on(), $equipment->do());
      }
    }
}
