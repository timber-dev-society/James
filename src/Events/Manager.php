<?php
declare(strict_types=1);
namespace James\Events;

class Manager
{
  /**
   * @var array<Event>
   */
  private $events;
  /**
   * @var array<callable>
   */
  private $listeners;

  public function addEvent(array $events): void
  {
    foreach ($events as list($name, $event)) {
      $this->events[$name] = $event;
      $this->listeners[$name] = [];
    }
  }

  public function attach(string $key, callable $callback): void
  {
    if (!isset($this->events[$key])) { return; }
    array_unshift($this->listeners[$key], $callback);
  }

  public function raise(string $key, ?array $args = null): void
  {
    if (!isset($this->events[$key])) { return; }
    $event = new $this->events[$key]($args);

    if (!$event instanceof Event) { return; }

    foreach ($this->listeners[$key] as $listener) {
      $listener($event);
    }
  }
}
