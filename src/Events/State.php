<?php
declare(strict_types=1);
namespace James\Events;

use James\Events\State\{ HasChange, HasNotChange };

final class State
{
  const HAS_CHANGE = HasChange::event;
  const HAS_NOT_CHANGE = HasNotChange::event;

  public function __construct(Manager $eventManager)
  {
    $eventManager->addEvents([
      [HasChange::event, HasChange::class],
      [HasNotChange::event, HasNotChange::class],
    ], $this);
  }
}
