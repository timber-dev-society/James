<?php
require 'vendor/autoload.php';

use PayBox\Parser;
use PayBox\Event;

$tractor = (new PayBox\Tractor('http://www1.paybox.com/espace-integrateur-documentation/infos-production/'))
    ->setGlobalSelector('.l-content-h.i-widgets .i-cf')
    ->setSectionSelector('.l-content-h.i-widgets .i-cf p strong');
$storage = new PayBox\Storage(__DIR__);

(new Parser($tractor, $storage))->on(Event::NOTHING_CHANGE, static function () {
  print 'Nothing has change' . PHP_EOL;

})->on(Event::SOMETHING_CHANGE, static function () {
  print 'Something has change' . PHP_EOL;

})->on(Event::NEW_SECTION, static function ($newSection) {
  print 'new event detected' . PHP_EOL;
  print $newSection->content . PHP_EOL;

})->on(Event::UPDATE_SECTION, static function ($newSection, $oldSection) {
  print 'event ' . $newSection->id . ' Updated' . PHP_EOL;
  $changes = str_replace($oldSection->content, '', $newSection->content);
  print $changes . PHP_EOL;

})->parse();

