<?php
require 'vendor/autoload.php';

use James\{ M, Q };
use James\Events\{ Content, State };

$spyCam = (new James\SpyCam(''))
    ->setGlobalSelector('.l-content-h.i-widgets .i-cf')
    ->setSectionSelector('.l-content-h.i-widgets .i-cf p strong');
$microfilm = new James\Microfilm(__DIR__);
$mission = new M('paybox', 'http://www1.paybox.com/espace-integrateur-documentation/infos-production/', '.l-content-h.i-widgets .i-cf p');

(new James\Bond($mission))->on(State::HAS_NOT_CHANGE, static function () {
  print 'Nothing has change' . PHP_EOL;

})->on(State::HAS_CHANGE, static function () {
  print 'Something has change' . PHP_EOL;

})->on(Content::ADDED, static function ($event) {
  print 'Content Added' . PHP_EOL;
  print $event->getLine() . PHP_EOL;

})->on(Content::UPDATED, static function ($event) {
  print 'Content Updated' . PHP_EOL;
  $changes = str_replace($event->getBefore(), '', $event->getAfter());
  print $changes . PHP_EOL;
});
