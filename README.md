# PHP Enum implementation inspired from SplEnum
# And forked by those who hate SplEnum and make some Java like behavior

## Why?

First, and mainly, `SplEnum` is not integrated to PHP, you have to install it separately.

Using an enum instead of class constants provides the following advantages:

- You can type-hint: `function setAction(Action $action) {`
- You can enrich the enum with methods (e.g. `format`, `parse`, …)
- You can extend the enum to add new values (make your enum `final` to prevent it)

## Declaration

```php
use LTDBeget\Enum\Enum;

/**
 * Action enum
 * @method static Action VIEW()
 * @method static Action EDIT()
 */
class Action extends Enum
{
    // when it will be php 7.1 it will be private const so better use only Action::VIEW() call style
    const VIEW = 'view';
    const EDIT = 'edit';
}
```

## Usage

```php
$action = Action::VIEW();
```

One advantage over using class constants is to be able to type-hint enum values:

```php
function setAction(Action $action) {
    // ...
}
```

## Documentation

- `name()` Returns the name of this enum constant, exactly as declared in its enum declaration.
- `__toString()` Returns the current value of the enum as string
- `equals(Enum $enum)` Returns true if the specified enum is equal to this

Static methods:

- `keys()` Returns the names (keys) of all constants in the Enum class
- `className()` Returns class name of this enum


Static method helpers are implemented using [`__callStatic()`](http://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic).

If you care about IDE autocompletion, you can use phpdoc (this is supported in PhpStorm for example):

```php
/**
 * @method static Action VIEW()
 * @method static Action EDIT()
 */
class Action extends Enum
{
    const VIEW = 'view';
    const EDIT = 'edit';
}
```

## Installation

```
composer require myclabs/php-enum
```
