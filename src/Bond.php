<?php
declare(strict_types=1);
namespace James;

use James\Events\{ State\HasChange, Collect\Before};

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

    $this->on(HasChange::event, function () {
      $this->resolveChanges();
    });
  }

  public function getMission(): M
  {
    return $this->mission;
  }

  /**
   * James start the mission
   */
  public function go(): void
  {
    $this->dispatch(Before::event);
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
    $args = [ 'OO7' => $this ] + $args;
    $this->eventManager->raise($event, $args);
  }
}
