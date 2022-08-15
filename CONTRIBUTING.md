Contributing
============

Thank you for contributing to this project!

Before we can merge your pull request here are some guidelines that you need to follow.
These guidelines exist not to annoy you, but to keep the code base clean,
unified and future proof.

Tests
-----

The project use [PHPUnit](https://phpunit.de/) tests. Please try to add/edit a test for your pull request.

You can run the tests by calling:

```shell
composer test
```

Code quality
------------

The project use static analysis:
- [Psalm](https://psalm.dev/)
- [PHPStan](https://phpstan.org/)

You can run them all by calling:

```shell
composer code-quality
```

Baselines
---------
Take into account that "baselines" of checkers added only for:
- ignoring of existing mistakes on the moment of checkers adding
- ignoring of known problems but which should not be fixed. 

**Don't update baselines without real reason!** 
