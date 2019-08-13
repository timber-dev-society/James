<?php
namespace James\Events;

abstract class Event
{
  private $stopped = false;

  public function __construct(array $params)
  {}

  public final function stopPropagation(): void
  {
    $this->stopped = true;
  }

  public final function isStop(): bool
  {
    return $this->stopped;
  }
}
