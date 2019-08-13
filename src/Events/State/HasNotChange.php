<?php
namespace James\Events\State;

use James\Events\Event;

final class HasNotChange extends Event
{
  public const event = 'STATE_HAS_NOT_CHANGE';

  public function __construct(?array $params)
  {}
}
