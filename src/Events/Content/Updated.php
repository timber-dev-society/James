<?php
declare(strict_types=1);
namespace James\Events\Content;

use James\Events\Event;

final class Updated extends Event
{
  public const event = 'CONTENT_UPDATED';

  private $deleted;
  private $added;

  public function __construct(array $args)
  {
    $this->deleted = $args['deleted'];
    $this->added = $args['added'];
  }

  public function getDeleted(): string
  {
    return $this->deleted;
  }

  public function getAdded(): string
  {
    return $this->added;
  }
}
