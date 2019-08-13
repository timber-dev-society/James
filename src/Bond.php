<?php
declare(strict_types=1);
namespace James;

use James\Events\State\HasChange;

class Bond
{
  /**
   * @var Events\Manager
   */
  private $eventManager;
  /**
   * @var SpyCam
   */
  private $mission;

  public function __construct(M $mission)
  {
    $this->mission = $mission;
    $this->initEvents();

    $this->on(Events\State\HasChange::event, function () {
      $this->resolveChanges();
    });
  }

  public function getMission(): M
  {
    return $this->mission;
  }

  public function getEquipment(Q $quartermaster): self
  {
    $quartermaster->equip($this);

    return $this;
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
    $this->eventManager->attach($event, $callable);

    return $this;
  }

  private function dispatch(string $event, ?array $args = null): void
  {
    $this->eventManager->raise($event, $args);
  }
}
