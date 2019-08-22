<?php
declare(strict_types=1);
namespace James;

use James\Events\{ State, Collect, Content };

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

    $this->on(State::HAS_CHANGE, function () {
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
    $this->dispatch(Collect::BEFORE);
  }

  public function getEquipment(Q $quartermaster): self
  {
    $quartermaster->equip($this);

    return $this;
  }

  private function initEvents(): void
  {
    $this->eventManager = new Events\Manager();

    new Content($this->eventManager);
    new State($this->eventManager);
    new Collect($this->eventManager);
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

  private function dispatch(string $event, ?array $args = []): void
  {
    $args = [ 'OO7' => $this ] + $args;
    $this->eventManager->raise($event, $args);
  }
}
