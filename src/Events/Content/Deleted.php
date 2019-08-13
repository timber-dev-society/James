<?php
namespace James\Events\Content;

use James\Events\Event;

final class Deleted extends Event
{
  public const event = 'CONTENT_DELETED';

  private $line;

  public function __construct(array $params)
  {
    $this->line = reset($params);
  }

  public function getLine()
  {
    return $this->line;
  }
}
