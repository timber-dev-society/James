<?php
namespace James;

class EventManager
{
  /**
   * @var array<Event>
   */
  private $events;
  /**
   * @var array<callable>
   */
  private $listeners;

  public function addEventListener(array $events): void
  {
    foreach ($events as list($name, $event)) {
      $this->events[$name] = $event;
      $this->listeners[$name] = [];
    }
  }

  public function attach(string $key, callable $callback): void
  {
    if (!isset($this->events[$key])) { return; }
    array_unshift($this->$listeners[$key], $callback);
  }

  public function raise(string $key, array $args)
  {
    if (!isset($this->events[$key])) { return false; }
    $event = new $this->events[$key]($args);

    foreach ($this->listeners[$key] as $listener) {
      $listener($event);
    }
  }
}
