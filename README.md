# Ignorable Observers

[![Code Climate](https://codeclimate.com/github/zachflower/ignorable-observers/badges/gpa.svg)](https://codeclimate.com/github/zachflower/ignorable-observers) [![Build Status](https://travis-ci.org/zachflower/ignorable-observers.svg?branch=master)](https://travis-ci.org/zachflower/ignorable-observers) [![Packagist](https://img.shields.io/packagist/v/zachflower/ignorable-observers.svg)]()

Dynamically disable/enable Laravel's Eloquent model observers. This library provides the ability to temporarily disable observable events for Eloquent models. For example, temporarily disable observers that kick off emails, push notifications, or queued calculations when performing a large number of database inserts or updates.

## Installation

Install using composer:

```
composer require zachflower/ignorable-observers
```

## Usage

To give an Eloquent model the ability to temporarily ignore observers, simply add the `IgnorableObservers` trait:

```php
<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use IgnorableObservers\IgnorableObservers;

class ExampleModel extends Model {
  use IgnorableObservers;
}
```

Then, simply call the `ignoreObservableEvents()` static method to ignore all observers for that model:

```php
ExampleModel::ignoreObservableEvents();
```

The `ignoreObservableEvents()` method also accepts an array of observers to be ignored. For example, the following line would ignore only the `saved` and `created` observers:

```php
ExampleModel::ignoreObservableEvents(['saved', 'created']);
```

To stop ignoring a model's observers, call the `unignoreObservableEvents()` static method:

```php
ExampleModel::unignoreObservableEvents();
```

The `unignoreObservableEvents()` method _also_ accepts an array of observers to unignore, giving you total control over which observers to enable and disable:

```php
ExampleModel::unignoreObservableEvents(['saved']);
```

### Example

The following example ignores any `saved` and `created` observers for the `ExampleModel`, inserts 100 rows into the database, and then "unignores" those observers when the operation is completed:

```php
ExampleModel::ignoreObservableEvents('saved', 'created');

for ( $i = 0; $i <= 100; $i++ ) {
  ExampleModel::create([
    'data' => $i
  ]);
}

ExampleModel::unignoreObservableEvents();
```

## Contributing

1. Fork it
1. Create your feature branch (`git checkout -b my-new-feature`)
1. Commit your changes (`git commit -am 'Add some feature'`)
1. Push to the branch (`git push origin my-new-feature`)
1. Create new [Pull Request](https://github.com/zachflower/ignorable-observers/compare)

## License

Ignorable Observers is an open-sourced library licensed under the MIT license.
