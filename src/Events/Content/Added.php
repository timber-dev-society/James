<?php
namespace James\Events\Content;

final class Added
{
  public const event = 'CONTENT_ADDED';

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
