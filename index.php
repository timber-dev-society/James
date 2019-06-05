<?php
require 'vendor/autoload.php';
use Goutte\Client;

// $pdo = new PDO('sql');
$i = 0;
$client = new Client();
$crawler = $client->request('GET', 'http://www1.paybox.com/espace-integrateur-documentation/infos-production/');
$nodes = $crawler->filter('.l-content-h.i-widgets .i-cf p strong');
print $nodes->count() . ' results' . PHP_EOL;
$nodes->each(static function ($node) use (&$i) {
    /* @var Symfony\Component\DomCrawler\Crawler $node */
    if (++$i === 1) {return;}

    print $node->text() . PHP_EOL . PHP_EOL;

    $contentNode = $node->parents()->nextAll();
    $content = '';
    do {
        if (strpos($contentNode->text(), '===') !== false) { break; }
        $content .= $contentNode->text() . PHP_EOL;
        $contentNode = $contentNode->nextAll();
    } while (true);

    print $content . PHP_EOL;

    print str_replace(['le', 'Le', ' ', '/'], '', $node->text()) . ' --- ' . sha1($content) . PHP_EOL . PHP_EOL;
    if ($i === 10) { exit(); }
});
