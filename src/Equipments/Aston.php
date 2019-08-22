<?php
declare(strict_types=1);
namespace James\Equipments;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;

use James\Events\{ Collect, Collect\Before };

/**
 * Class SpyCam
 *
 * @package James
 */
class Aston implements GearInterface
{

  private function getDocument(string $method, string $url): ResponseInterface
  {
    $client = new Client();
    return $client->request($method, $url);
  }

  private function getContent(string $dom, string $selector): array
  {
    $crawler = new Crawler(null, null);
    $crawler->addContent($dom, 'text/html');
    $nodes = [];
    $crawler->filter($selector)->each(static function ($node) use (&$nodes) {
      $nodes[] = $node->text();
    });

    return $nodes;
  }

  public function on(): string
  {
    return Collect::BEFORE;
  }

  public function do(): callable
  {
    return function (Before $event) {
      $OO7 = $event->getOO7();
      $mission = $OO7->getMission();

      $document = $this->getDocument($mission->getMethod(), $mission->getUrl());
      $dom = $document->getBody()->getContents();
      $content = $this->getContent($dom, $mission->getSelector());

      $OO7->dispatch(Collect::AFTER,[
        'dom' => $dom,
        'content' => implode(PHP_EOL, $content) . PHP_EOL,
      ]);
    };
  }
}
