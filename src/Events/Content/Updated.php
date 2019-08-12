<?php
namespace James\Events\Content;

final class Updated
{
  public const event = 'CONTENT_UPDATED';

  private $before;
  private $after;

  public function __construct(array $params)
  {
    list($before, $after) = $params;
    $this->line = reset($params);
  }

  public function getLine()
  {
    return $this->after;
  }

  public function getBefore()
  {
    return $this->before;
  }

  public function getAfter()
  {
    return $this->after;
  }
}
