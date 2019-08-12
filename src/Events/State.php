<?php
namespace James\Events;

use Doctrine\Common\EventArgs;
use Doctrine\Common\EventManager;

final class State
{
  public const CHANGE = 'change';
  public const NOT_CHANGE = 'notChange';

  /** @var EventManager */
  private $eventManager;

  public function __construct(EventManager $eventManager)
  {
    $this->eventManager = $eventManager;
    $this->eventManager->addEventListener([
      self::CHANGE,
      self::NO_CHANGE,
    ], $this);
  }

  public function change(EventArgs $eventArgs) : void
  {
  }

  public function notChange(EventArgs $eventArgs) : void
  {
  }
}
