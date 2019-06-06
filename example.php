<?php
require 'vendor/autoload.php';

use PayBox\Parser;

(new Parser(__DIR__))->on(Parser::NOTHING_CHANGE_EVENT, static function () {
  print 'Nothing has change' . PHP_EOL;

})->on(Parser::SOMETHING_CHANGE_EVENT, static function () {
  print 'Something has change' . PHP_EOL;

})->on(Parser::NEW_EVENT, static function ($newSection) {
  print 'new event detected' . PHP_EOL;
  print $newSection->content . PHP_EOL;

})->on(Parser::UPDATE_EVENT, static function ($newSection, $oldSection) {
  print 'event ' . $newSection->id . ' Updated' . PHP_EOL;
  $changes = str_replace($oldSection->content, '', $newSection->content);
  print $changes . PHP_EOL;

})->parse();

