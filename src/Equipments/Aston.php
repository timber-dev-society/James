<?php
declare(strict_types=1);
namespace James\Equipments;


use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

use James\Events\Collect\{ Before, After };

/**
 * Class SpyCam
 *
 * @package James
 */
class Aston implements GearInterface
{
  /**
   * @return Crawler
   */
  private function getDom()
  {
    $client = new Client();
    return $client->request($this->method, $this->uri);
  }

  public function on()
  {
    return After::event;
  }

  public function do(): callable
  {
    return function (Before $event) {
      $OO7 = $event->getOO7();
      $mission = $OO7->getMission();

      $dom = $this->getDom($mission->getMethod(), $mission->getUrl());

      $OO7->dispatch(After::event,[
        'dom' => $dom,
        'content' => $dom->filter($mission->getSelector()),
      ]);
    };
  }
}
