<?php
namespace James;


use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class SpyCam
 *
 * @package James
 */
class SpyCam
{
    /**
     * @var string
     */
    private $uri;
    /**
     * @var string
     */
    private $method;
    /**
     * @var Crawler
     */
    private $dom;
    /**
     * @var string
     */
    private $globalSelector;
    /**
     * @var string
     */
    private $sectionSelector;

    /**
     * Tractor constructor.
     * @param $uri
     * @param string $method
     */
    public function __construct($uri, $method = 'GET')
    {
        $this->method = $method;
        $this->uri = $uri;
    }

    public function start()
    {
        $this->dom = $this->getDom();
    }

    /**
     * @param string $selector
     * @return $this
     */
    public function setSectionSelector($selector)
    {
        $this->sectionSelector = $selector;

        return $this;
    }

    /**
     * @return Crawler
     */
    private function getDom()
    {
        $client = new Client();
        return $client->request($this->method, $this->uri);
    }

    /**
     * @param string
     * @return bool
     */
    public function somethingHasChange($oldGlobalState)
    {
        $globalState = sha1($this->dom->filter($this->globalSelector)->text());

        return $globalState === $oldGlobalState ? false : $globalState;
    }

    /**
     * @return Crawler
     */
    public function getSections()
    {
        return $this->dom->filter($this->sectionSelector);
    }
}
