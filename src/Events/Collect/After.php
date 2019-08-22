<?php
declare(strict_types=1);
namespace James\Events\Collect;

use James\Events\Event;

final class After extends Event
{
  public const event = 'AFTER_DATA_COLLECT';

  private $dom;
  private $content;

  public function __construct(array $args)
  {
    parent::__construct($args);

    $this->dom = $args['dom'];
    $this->content = $args['content'] ?? null;
  }

  public function getDom()
  {
    return $this->dom;
  }

  public function getContent()
  {
    return $this->content
  }
}
