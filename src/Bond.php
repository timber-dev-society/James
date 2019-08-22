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

  private $memory = [];

  public function __construct(M $mission)
  {
    $this->mission = $mission;
    $this->initEvents();
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

  public function dispatch(string $event, ?array $args = []): void
  {
    $args = [ 'OO7' => $this ] + $args;
    $this->eventManager->raise($event, $args);
  }

  public function save($key, $data): void
  {
    $this->memory[$key] = $data;
  }

  public function get($key, $default = null)
  {
    return $this->memory[$key] ?? $default;
  }
}
