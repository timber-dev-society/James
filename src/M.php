<?php
declare(strict_types=1);
namespace James;

class M
{
    private $id;
    private $url;
    private $selector;

    /**
     * @param $id
     * @param $url
     * @param $selector
     *
     * @return self
     */
    public function __construct(string $id, string $url, string $selector)
    {
        $this->id = $id;
        $this->url = $url;
        $this->selector = $selector;
    }

    public function getPathname(): string
    {
      return $this->id . '/content';
    }

    public function getRawPathname(): string
    {
      return $this->id . 'raw';
    }
}
