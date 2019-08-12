<?php
namespace James\Events;
use James\EventsContent\{ Added, Updated, Deleted };

final class ContentDispatcher
{
  public const ADDED = Content\Added::event;
  public const UPDATED = Content\Updated::event;
  public const DELETED = Content\Deletd::event;

  public const ADDED_CLASS = Content\Added::class;
  public const UPDATED_CLASS = Content\Updated::class;
  public const DELETED_CLASS = Content\Deletd::class;

  /** @var EventManager */
  private $eventManager;

  public function __construct(EventManager $eventManager)
  {
    $this->eventManager = $eventManager;
    $this->eventManager->addEventListener([
      [self::ADDED, ],
      [self::UPDATED,],
      [self::DELETED, ],
    ], $this);
  }

}
