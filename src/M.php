<?php
declare(strict_types=1);
namespace James;

class M
{
    private $id;
    private $url;
    private $selector;
    private $method;

    /**
     * @param $id
     * @param $url
     * @param $selector
     *
     * @return self
     */
    public function __construct(string $id, string $url, string $selector, string $method = 'GET')
    {
        $this->id = $id;
        $this->url = $url;
        $this->selector = $selector;
        $this->method = $method;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
      return $this->url;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
      return $this->method;
    }

    public function getSelector(): string
    {
      return $this->selector;
    }

    public function getPathname(): string
    {
      return $this->id . '/content';
    }

    public function getRawPathname(): string
    {
      return $this->id . '/raw';
    }
}
