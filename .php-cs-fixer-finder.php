<?php

declare(strict_types=1);

use PhpCsFixer\Finder;

return (new Finder())
    ->in('src')
    ->in('static-analysis')
    ->in('stubs')
    ->in('tests')
    ->append([
        'bin/dev/php-cs-fixer-update-base-line',
        '.php-cs-fixer.dist.php',
        '.php-cs-fixer-finder.php',
    ]);
