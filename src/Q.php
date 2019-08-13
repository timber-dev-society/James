<?php
declare(strict_types=1);
namespace James;
use James\Bond;

class M
{
    private $stuff = [];

    public function addStuff($stuff): void
    {
      $this->stuff[] = $stuff;
    }

    public function equip(Bond $jamesBond)
    {
      foreach ($this->stuff as $stuff) {
        $jamesBond->attach($stuff->on(), $stuff->do());
      }
    }
}
