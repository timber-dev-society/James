<?php
declare(strict_types=1);
namespace James\Events\Content;

use James\Events\Event;

final class Updated extends Event
{
  public const event = 'CONTENT_UPDATED';

  private $before;
  private $after;

  public function __construct(?array $params)
  {
    list($before, $after) = $params;
    $this->line = reset($params);
  }

  public function getLine(): string
  {
    return $this->after;
  }

  public function getBefore(): string
  {
    return $this->before;
  }

  public function getAfter(): string
  {
    return $this->after;
  }
}
