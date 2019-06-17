# Super autonomous website content spy

## usage

### Base

```php
require 'vendor/autoload.php';

$spyCam = (new James\SpyCam('https://www.google.com/search?q=James+Bond'))
    ->setGlobalSelector('#search')
    ->setSectionSelector('#search .rc .r');
$microfilm = new James\Microfilm(__DIR__, 'JamesBond');

(new James\Bond($spyCam, $microfilm))->on('event', function () {
  'Do what you whant';
})->parse()
```

### Events

To see a full example watch example.php

#### SOMETHING_CHANGE_EVENT

```php
// ...

(new James\Bond($spyCam, $microfilm))->on(James\Event::SOMETHING_CHANGE, function () {
  print 'Something new or updated' . PHP_EOL;
})->parse()
```

#### NOTHING_CHANGE_EVENT

```php
// ...

(new James\Bond($spyCam, $microfilm))->on(James\Event::NOTHING_CHANGE, function () {
  print 'Nothing append from the last time' . PHP_EOL;
})->parse()
```


#### NEW_EVENT

```php
// ...

(new James\Bond($spyCam, $microfilm))->on(James\Event::NEW_SECTION, function ($section) {
  print 'Something new append' . PHP_EOL;
  print $section->content . PHP_EOL;
})->parse()
```


#### UPDATE_EVENT

```php
// ...

(new James\Bond($spyCam, $microfilm))->on(James\Event::UPDATE_SECTION, function ($newSection, $oldSection) {
  print 'Something has been updated' . PHP_EOL;
  print 'before : ' . PHP_EOL;
  print $oldSection->content . PHP_EOL;
  print 'after : ' . PHP_EOL;
  print $newSection->content . PHP_EOL;
})->parse()
```
