<?php
declare(strict_types=1);
namespace James\Events\State;

use James\Events\Event;

final class HasChange extends Event
{
  public const event = 'STATE_HAS_CHANGE';

  public function __construct(?array $params)
  {}
}
