<?php
namespace James\Events;
use James\Events\Content\{ Added, Updated, Deleted };
use James\EventManager;

final class ContentDispatcher
{
  /** @var EventManager */
  private $eventManager;

  public function __construct(EventManager $eventManager)
  {
    $this->eventManager = $eventManager;
    $this->eventManager->addEventListener([
      [Added::event, Added::class],
      [Updated::event, Updated::class],
      [Deleted::event, Deleted::class],
    ], $this);
  }
}
