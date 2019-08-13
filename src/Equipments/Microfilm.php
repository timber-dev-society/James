<?php
namespace James;

use Symfony\Component\Filesystem\Filesystem;
use James\Events\Collect\After;
use James\Events\Event;

class Microfilm
{
  private $fs;
  private $dir;

  public function __construct(string $storageDir)
  {
    $this->fs = new Filesystem();
    $this->dir = $storageDir;
    if (!$this->fs->exists($this->dir)) {
      $this->fs->mkdir($this->dir);
    }
  }

  public function on()
  {
    return After::event;
  }

  public function do(): callable
  {
    return function (After $event) {
      $james = $event->getOO7();

      $mission = $james->getMission();

      if (!$this->fs->exists("{$this->dir}/{$mission->getRawPathname()}")) {

      }
      if (!$this->fs->exists("{$this->dir}/{$mission->getPathname()}")) {

      }
      $event->getGlobalContent();
    };
  }
}
