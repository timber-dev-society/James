<?php
declare(strict_types=1);
namespace James\Tests\Events;

use PHPUnit\Framework\TestCase;
use James\Events\{ Manager, Event, State\HasChange };

class ManagerTest extends TestCase
{
  public function testAddEvent()
  {
    $manager = new Manager();

    $manager->addEvent([[HasChange::event, HasChange::class]]);

    $manager->attach(HasChange::event, function ($event) {
      $this->assertInstanceOf(Event::class, $event);
    });

    $manager->raise(HasChange::event);
  }
}
