<?php
namespace James\Events\State;

final class HasChange
{
  public const event = 'STATE_HAS_CHANGE';

  public function __construct(array $params)
  {}
}
