Contributing
============

Thank you for contributing to this project!

Before we can merge your pull request here are some guidelines that you need to follow.
These guidelines exist not to annoy you, but to keep the code base clean,
unified and future proof.

Tests
-----

Please try to add a test for your pull request.

You can run the tests by calling:

```shell
composer phpunit
```

Code quality
------------

Checking code standard, benchmark, and more.

```shell
composer code-quality
```

Coding Standard
---------------

You can use to fix coding standard if needed:

```shell
composer cs-fix
```

Baselines
---------
Take into account that "baselines" of checkers added only for:
- ignoring of existing mistakes on the moment of checkers adding
- ignoring of known problems but which should not be fixed. 

**Don't update baselines without real reason!** 
