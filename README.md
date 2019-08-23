# Super autonomous website content spy

## usage

### Base

```php
require 'vendor/autoload.php';

use James\{ Bond as OO7, M, Q, Equipments };

$q = (new Q())->addEquipment(new Equipments\Aston())
              ->addEquipment(new Equipments\Microfilm('/path/to/store/data'))
              ->addEquipment(new Equipments\Scanner());

$mission = new M('job-id', 'http://www.url-to-track.com', '.content:selector');

$OO7 = (new OO7($mission))->getEquipment($q);

$OO7->go();
```

### Events

To see a full fonctionnal example watch example.php

#### Something has change

```php
// ...
use James\Events\State;

$OO7->on(State::HAS_CHANGE, function () {
  print 'Something new or updated' . PHP_EOL;
})->go();
```

#### Nothing has change

```php
// ...
use James\Events\State;

$OO7->on(State::HAS_NOT_CHANGE, function () {
  print 'Nothing append from the last time' . PHP_EOL;
})->go();
```


#### Add event

```php
// ...
use James\Events\Content;

$OO7->on(James\Content::ADDED, function ($event) {
  print 'New content available' . PHP_EOL;
  print $event->getAdded() . PHP_EOL;
})->go();
```


#### Update event

```php
// ...
use James\Events\Content;

$OO7->on(Content::UPDATED, function ($event) {
  print 'Content has been updated' . PHP_EOL;
  print 'before : ' . PHP_EOL;
  print $event->getDeleted() . PHP_EOL;
  print 'after : ' . PHP_EOL;
  print $event->getAdded() . PHP_EOL;
})->go();
```

#### Delete event

```php
// ...
use James\Events\Content;

$OO7->on(Content::DELETED, function ($event) {
  print 'Content has been removed' . PHP_EOL;
  print $event->getDeleted() . PHP_EOL;
})->go();
```
