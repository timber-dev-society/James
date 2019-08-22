<?php
declare(strict_types=1);
namespace James\Events;

abstract class Event
{
  private $stopped = false;

  private $OO7 = null;

  public function __construct(?array $params)
  {
    $this->OO7 = $params['OO7'];
  }

  public final function stopPropagation(): void
  {
    $this->stopped = true;
  }

  public final function isStop(): bool
  {
    return $this->stopped;
  }

  public function getOO7(): James\Bond
  {
    return $this->OO7;
  }
}
