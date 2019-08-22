<?php
declare(strict_types=1);
namespace James;

class Q
{
    private $equipments = [];

    public function addEquipment(Equipments\GearInterface $equipment): self
    {
      $this->equipments[] = $equipment;

      return $this;
    }

    public function equip(Bond $OO7): void
    {
      foreach ($this->equipments as $equipment) {
        $OO7->on($equipment->on(), $equipment->do());
      }
    }
}
