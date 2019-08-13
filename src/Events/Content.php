<?php
declare(strict_types=1);
namespace James\Events;

use James\Events\Content\{ Added, Updated, Deleted };

final class Content
{
  const ADDED = Added::event;
  const UPDATED = Updated::event;
  const DELETED = Deleted::event;

  /** @var EventManager */
  private $eventManager;

  public function __construct(Manager $eventManager)
  {
    $this->eventManager = $eventManager;
    $this->eventManager->addEventListener([
      [Added::event, Added::class],
      [Updated::event, Updated::class],
      [Deleted::event, Deleted::class],
    ], $this);
  }
}
