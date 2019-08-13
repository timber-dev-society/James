<?php
namespace James;

use James\Events\State\HasChange;

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

    $this->on(Events\State\HasChange::event, function () {
      $this->resolveChanges();
    });
  }

  private function initEvents(): void
  {
    $this->eventManager = new Events\Manager();
    new Events\Content($eventManager);
    new Events\State($eventManager);
  }

  /**
   * @param string $event
   * @param callable $callable
   *
   * @return Bond;
   */
  public function on(string $event, callable $callable): self
  {
    $this->eventManager->attach(($event, $callable);

    return $this;
  }

  private function dispatch(string $event): void
  {
    $this->eventManager->dispatch($event);
  }
}
