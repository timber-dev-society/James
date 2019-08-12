<?php
namespace James;

use Doctrine\Common\EventManager;

class Bond
{
  /**
   * @var EventManager
   */
  private $eventManager;
  /**
   * @var SpyCam
   */
  private $spyCam;

  public function __construct($spyCam)
  {
    $this->spyCam = $spyCam;
    $this->initEvents();

    $this->on(Event::SOMETHING_CHANGE, function () {
      $this->resolveChanges();
    });
  }

  private function initEvents()
  {
    $this->eventManager = new EventManager();
    new Events\Content($eventManager);
    new Events\State($eventManager);
  }

  /**
   * @param string $event
   * @param callable $callable
   *
   * @return Bond;
   */
  public function on($event, callable $callable)
  {
    array_unshift($this->events[$event], $callable);

    return $this;
  }

  private function dispatch($event)
  {
    $this->eventManager->dispatch($event);
  }
}
