<?php
declare(strict_types=1);
namespace James\Events;

use James\Bond as OO7;

abstract class Event
{
  private $stopped = false;

  private $OO7 = null;

  public function __construct(array $args)
  {
    $this->OO7 = $args['OO7'];
  }

  public final function stopPropagation(): void
  {
    $this->stopped = true;
  }

  public final function isStop(): bool
  {
    return $this->stopped;
  }

  public function getOO7(): OO7
  {
    return $this->OO7;
  }
}
