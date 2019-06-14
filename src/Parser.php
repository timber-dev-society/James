<?php

namespace PayBox;

use Symfony\Component\DomCrawler\Crawler;

class Parser
{
  /**
   * @var array
   */
  private $events = [];
  /**
   * @var Storage
   */
  private $store;
  /**
   * @var Tractor
   */
  private $tractor;

  public function __construct($tractor, $storage)
  {
    $this->store = $storage;
    $this->tractor = $tractor;

    $this->on(Event::NEW_SECTION, function ($newSection) {
      $this->store->create($newSection);
    });

    $this->on(Event::UPDATE_SECTION, function ($newSection, $oldSection) {
      $this->store->update($newSection);
    });

    $this->on(Event::SOMETHING_CHANGE, function () {
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
    $this->tractor->start();

    $newState = $this->somethingHasChange();

    if (!$newState) {
      $this->dispatch(Event::NOTHING_CHANGE);
      return;
    }

    $this->dispatch(Event::SOMETHING_CHANGE);

    $section = Section::create('global', $newState);
    $this->store->update($section);
  }

  private function dispatch($event, $newSection = null, $oldSection = null)
  {
    if (!isset($this->events[$event])) { return; }

    switch ($event) {
      case Event::NOTHING_CHANGE:
      case Event::SOMETHING_CHANGE:
        foreach ($this->events[$event] as $callable) {
          $callable();
        }
        break;
      case Event::NEW_SECTION:
        foreach ($this->events[$event] as $callable) {
          $callable($newSection);
        }
        break;
      case Event::UPDATE_SECTION:
        foreach ($this->events[$event] as $callable) {
          $callable($newSection, $oldSection);
        }
        break;
    }
  }

  private function resolveChanges()
  {
    $i = 0;
    $this->tractor->getSections()->each(function ($node) use (&$i) {
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
      $event = $oldSection === false ? Event::NEW_SECTION : Event::UPDATE_SECTION;

      $this->dispatch($event, $newSection, $oldSection);
    });
  }

  /**
   * @return bool
   */
  private function somethingHasChange()
  {
    $globalSection = $this->store->get('global');

    return $this->tractor->somethingHasChange($globalSection->state);
  }
}