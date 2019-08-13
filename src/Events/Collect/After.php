<?php
declare(strict_types=1);
namespace James\Events\Collect;

use James\Events\Event;

final class After extends Event
{
  public const event = 'AFTER_DATA_COLLECT';

  public function __construct(?array $params)
  {}
}
