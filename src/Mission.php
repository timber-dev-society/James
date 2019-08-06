<?php

namespace James;


class Mission
{
    public $id;
    public $state;
    public $content;

    /**
     * @param $id
     * @param $state
     * @param $content
     *
     * @return self
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
        return $this->id;
    }
}