<?php
namespace PayBox;

class Section
{
  public $id;
  public $state;
  public $content;

  /**
   * @param $id
   * @param $state
   * @param $content
   * @return Section
   */
  public static function create($id, $state, $content = '')
  {
    $self = new self();
    $self->id = $id;
    $self->state = $state;
    $self->content = $content;

    return $self;
  }

  public static function buildId($text)
  {
    return str_replace(['le', 'Le', ' ', '/', ':'], '', $text);
  }
}