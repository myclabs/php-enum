# PHP Enum implementation inspired from SplEnum


## Why?

Using an enum instead of class constants provides the following advantages:

- You can type-hint: `function setAction(Action $action) {`
- You can enrich the enum with methods (e.g. `format`, `parse`, …)
- You can extend the enum to add new values (make your enum `final` to prevent it)
- You can get a list of all the possible values (see below)

This Enum class is not intended to replace class constants, but only to be used when it makes sense.


## Declaration

```php
use Mycsense\Enum\Enum;

/**
 * Action enum
 */
class Action extends Enum
{
    const VIEW = 'view';
    const EDIT = 'edit';

    /**
     * @return Action
     */
    public static function VIEW() {
        return new Action(self::VIEW);
    }

    /**
     * @return Action
     */
    public static function EDIT() {
        return new Action(self::EDIT);
    }
}
```

Implementing the static methods `VIEW()` and `EDIT()` is optional, it only serves as shortcut to `new Action(Action::VIEW)`.


## Usage

```php
$action = Action::VIEW();

// or
$action = new Action(Action::VIEW);
```

One advantage over using class constants is to be able to type-hint enum values:

```
function setAction(Action $action) {
    // ...
}
```

## Documentation

- `__construct()` The constructor checks that the value exist in the enum
- `__toString()` You can `echo $myValue`, it will display the enum value (value of the constant)
- `getValue()` Returns the current value of the enum
- `toArray()` (static) Returns an array of all possible values (constant name in key, constant value in value)
