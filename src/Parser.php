<?php

namespace PayBox;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
  const NEW_EVENT = 'new';
  const UPDATE_EVENT = 'update';
  const NOTHING_CHANGE_EVENT = 'nothing_change';
  const SOMETHING_CHANGE_EVENT = 'something_change';

  /**
   * @var array
   */
  private $events = [];
  /**
   * @var Storage
   */
  private $store;
  /**
   * @var Crawler
   */
  private $dom;

  public function __construct($storageDir)
  {
    $this->store = new Storage($storageDir);

    $this->on(self::NEW_EVENT, function ($newSection) {
      $this->store->create($newSection);
    });

    $this->on(self::UPDATE_EVENT, function ($newSection, $oldSection) {
      $this->store->update($newSection);
    });

    $this->on(self::SOMETHING_CHANGE_EVENT, function () {
      $this->resolveChanges();
    });
  }

  /**
   * @param string $event
   * @param callable $callable
   *
   * @return Parser;
   */
  public function on($event, callable $callable)
  {
    if (!isset($this->events[$event])) {
      $this->events[$event] = [];
    }
    array_unshift($this->events[$event], $callable);

    return $this;
  }

  /**
   * Parse Page
   */
  public function parse()
  {
    $this->dom = $this->getDom();

    $newState = $this->isSomethingHasChange();
    if (!$newState) {
      $this->dispatch(self::NOTHING_CHANGE_EVENT);
      return;
    }

    $this->dispatch(self::SOMETHING_CHANGE_EVENT);

    $section = Section::create('global', $newState);
    $this->store->update($section);
  }

  private function dispatch($event, $newSection = null, $oldSection = null)
  {
    if (!isset($this->events[$event])) { return; }

    switch ($event) {
      case self::NOTHING_CHANGE_EVENT:
      case self::SOMETHING_CHANGE_EVENT:
        foreach ($this->events[$event] as $callable) {
          $callable();
        }
        break;
      case self::NEW_EVENT:
        foreach ($this->events[$event] as $callable) {
          $callable($newSection);
        }
        break;
      case self::UPDATE_EVENT:
        foreach ($this->events[$event] as $callable) {
          $callable($newSection, $oldSection);
        }
        break;
    }
  }

  private function resolveChanges()
  {
    $i = 0;
    $nodes = $this->dom->filter('.l-content-h.i-widgets .i-cf p strong');

    $nodes->each(function ($node) use (&$i) {
      /* @var Crawler $node */
      if (($i === 10) || (++$i === 1)) { return; }

      $sectionId = Section::buildId($node->text());

      $oldSection = $this->store->get($sectionId);

      $contentNode = $node->parents()->nextAll();
      $content = '';
      $j = 0;
      do {
        if (++$j === 20) { break; } //something is wrong
        if (strpos($contentNode->text(), '===') !== false) { break; }

        $content .= $contentNode->text() . PHP_EOL;
        $contentNode = $contentNode->nextAll();
      } while (true);

      $state = sha1($content);
      if ($oldSection !== false && $state === $oldSection->state) { return; }

      $newSection = Section::create($sectionId, $state, $content);
      $event = $oldSection === false ? self::NEW_EVENT : self::UPDATE_EVENT;

      $this->dispatch($event, $newSection, $oldSection);
    });
  }

  /**
   * @return bool
   */
  private function isSomethingHasChange()
  {
    $globalState = sha1($this->dom->filter('.l-content-h.i-widgets .i-cf p strong')->text());
    $globalSection = $this->store->get('global');

    return $globalSection->state === $globalState ? false : $globalState;
  }

  /**
   * @return Crawler
   */
  private function getDom()
  {
    $client = new Client();
    return $client->request('GET', 'http://www1.paybox.com/espace-integrateur-documentation/infos-production/');
  }
}