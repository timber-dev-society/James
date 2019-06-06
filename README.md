# PayBox info production page parser

## usage

### Base

```php
require 'vendor/autoload.php';

use PayBox\Parser;

(new Parser(__DIR__))->on('event', function () {
  'Do what you whant';
})->parse()
```

### Events

To see a full example watch example.php

#### SOMETHING_CHANGE_EVENT

```php
use PayBox\Parser;

(new Parser(__DIR__))->on(Parser::SOMETHING_CHANGE_EVENT, function () {
  print 'Something new or updated' . PHP_EOL;
})->parse()
```

#### NOTHING_CHANGE_EVENT

```php
use PayBox\Parser;

(new Parser(__DIR__))->on(Parser::NOTHING_CHANGE_EVENT, function () {
  print 'Nothing append from the last time' . PHP_EOL;
})->parse()
```


#### NEW_EVENT

```php
use PayBox\Parser;

(new Parser(__DIR__))->on(Parser::NEW_EVENT, function ($section) {
  print 'Something new append' . PHP_EOL;
  print $section->content . PHP_EOL;
})->parse()
```


#### UPDATE_EVENT

```php
use PayBox\Parser;

(new Parser(__DIR__))->on(Parser::NEW_EVENT, function ($newSection, $oldSection) {
  print 'Something has been updated' . PHP_EOL;
  print 'before : ' . PHP_EOL;
  print $oldSection->content . PHP_EOL;
  print 'after : ' . PHP_EOL;
  print $newSection->content . PHP_EOL;
})->parse()
```
