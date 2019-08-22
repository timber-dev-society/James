<?php
require 'vendor/autoload.php';

use James\{ M, Q, Bond as OO7, Equipments };
use James\Events\{ Content, State };

$q = (new Q())->addEquipment(new Equipments\Aston())
              ->addEquipment(new Equipments\Microfilm(__DIR__ . '/data'));

$mission = new M('paybox', 'http://www1.paybox.com/espace-integrateur-documentation/infos-production/', '.l-content-h.i-widgets .i-cf p');

(new OO7($mission))->getEquipment($q)
  ->on(State::HAS_NOT_CHANGE, static function () {
    print 'Nothing has change' . PHP_EOL;

  })->on(State::HAS_CHANGE, static function () {
    print 'Something has change' . PHP_EOL;

  })->on(Content::ADDED, static function ($event) {
    print 'Content Added' . PHP_EOL;
    print $event->getAdded() . PHP_EOL;

  })->on(Content::UPDATED, static function ($event) {
    print 'Content Updated' . PHP_EOL;
    $changes = str_replace($event->getDeleted(), '', $event->getAdded());
    print $changes . PHP_EOL;

  })->on(Content::DELETED, static function ($event) {
    print 'Content Deleted' . PHP_EOL;
    print $event->getDeleted() . PHP_EOL;
    print $changes . PHP_EOL;
  });
