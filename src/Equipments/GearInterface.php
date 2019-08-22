<?php
declare(strict_types=1);
namespace James\Equipments;

use James\Events\Event;

interface GearInterface
{
  public function on(): string;

  public function do(): callback;
}
