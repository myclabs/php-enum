# PHP Enum implementation inspired from SplEnum

[![Build Status](https://travis-ci.org/myclabs/php-enum.png?branch=master)](https://travis-ci.org/myclabs/php-enum)
[![Latest Stable Version](https://poser.pugx.org/myclabs/php-enum/version.png)](https://packagist.org/packages/myclabs/php-enum)
[![Total Downloads](https://poser.pugx.org/myclabs/php-enum/downloads.png)](https://packagist.org/packages/myclabs/php-enum)

## Why?

First, and mainly, `SplEnum` is not integrated to PHP, you have to install it separately.

Using an enum instead of class constants provides the following advantages:

- You can type-hint: `function setAction(Action $action) {`
- You can enrich the enum with methods (e.g. `format`, `parse`, …)
- You can extend the enum to add new values (make your enum `final` to prevent it)
- You can get a list of all the possible values (see below)

This Enum class is not intended to replace class constants, but only to be used when it makes sense.

## Installation

```
composer require myclabs/php-enum
```

## Declaration

```php
use MyCLabs\Enum\Enum;

/**
 * Action enum
 */
class Action extends Enum
{
    const VIEW = 'view';
    const EDIT = 'edit';
}
```


## Usage

```php
$action = Action::VIEW();
```

As you can see, static methods are automatically implemented to provide quick access to an enum value.

One advantage over using class constants is to be able to type-hint enum values:

```php
function setAction(Action $action) {
    // ...
}
```

Each Enum instance for a given key is a singleton, so you can use:

```php
function setAction(Action $action) {
    if ($action === Action::VIEW()) {
        //
    }
}
```

**Note** that this is not true, if you `unserialize()` Enums.
In case another Enum instance already exists,
an `E_USER_NOTICE` is triggered.

## Documentation

- `__toString()` You can `echo $myValue`, it will display the enum value (value of the constant)
- `getValue()` Returns the current value of the enum
- `getKey()` Returns the key of the current value on Enum
- `equals()` Tests whether enum instances are equal (returns `true` if enum values are equal, `false` otherwise)

Static methods:

- `toArray()` method Returns all possible values as an array (constant name in key, constant value in value)
- `keys()` Returns the names (keys) of all constants in the Enum class
- `values()` Returns instances of the Enum class of all Enum constants (constant name in key, Enum instance in value)
- `isValid()` Check if tested value is valid on enum set
- `isValidKey()` Check if tested key is valid on enum set
- `search()` Return key for searched value
- `fromKey()` Return Enum instance for the given key
- `fromValue()` Return Enum instance for the given value

### Static methods

```php
class Action extends Enum
{
    const VIEW = 'view';
    const EDIT = 'edit';
}

// Static method:
$action = Action::VIEW();
$action = Action::EDIT();
```

Static method helpers are implemented using [`__callStatic()`](http://www.php.net/manual/en/language.oop5.overloading.php#object.callstatic).

If you care about IDE autocompletion,
you can use phpdoc (this is supported in PhpStorm for example):

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

## Related projects

- [Doctrine enum mapping](https://github.com/acelaya/doctrine-enum-type)
- [Symfony 2/3 ParamConverter integration](https://github.com/Ex3v/MyCLabsEnumParamConverter)
