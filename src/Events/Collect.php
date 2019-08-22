<?php
declare(strict_types=1);
namespace James\Events;

use James\Events\Collect\{ Before, After };

final class Collect
{
  const BEFORE = Before::event;
  const AFTER = After::event;

  public function __construct(Manager $eventManager)
  {
    $eventManager->addEvents([
      [Before::event, Before::class],
      [After::event, After::class],
    ], $this);
  }
}
