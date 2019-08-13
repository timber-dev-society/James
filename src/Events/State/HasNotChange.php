<?php
namespace James\Events\State;

final class HasNotChange
{
  public const event = 'STATE_HAS_NOT_CHANGE';

  public function __construct(array $params)
  {}
}
