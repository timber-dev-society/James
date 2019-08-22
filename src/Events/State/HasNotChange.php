<?php
declare(strict_types=1);
namespace James\Events\State;

use James\Events\Event;

final class HasNotChange extends Event
{
  public const event = 'STATE_HAS_NOT_CHANGE';
}
