<?php
declare(strict_types=1);
namespace James\Events\Content;

use James\Events\Event;

final class Added extends Event
{
  public const event = 'CONTENT_ADDED';

  private $line;

  public function __construct(array $args)
  {
    $this->line = $args['added'];
  }

  public function getAdded(): string
  {
    return $this->line;
  }
}
