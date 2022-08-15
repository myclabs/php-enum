<?php

declare(strict_types=1);

use PhpCsFixer\Finder;

$rules = [
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'concat_space' => [
        'spacing' => 'one',
    ],
    'class_definition' => [
        'single_item_single_line' => true,
    ],
    'method_chaining_indentation' => true,
    'no_superfluous_phpdoc_tags' => true,
    'phpdoc_align' => false,
    'phpdoc_annotation_without_dot' => false,
    'phpdoc_no_alias_tag' => false,
    'phpdoc_summary' => false,
    'phpdoc_to_comment' => false,
    'single_line_throw' => false,
    'void_return' => true,
];

function createFinder(): Finder
{
    $baseLine = [];
    $baselinePath = __DIR__ . '/.php-cs-fixer-baseline.json';
    if (file_exists($baselinePath)) {
        /** @var array<string,array{ hash: int }> $baseLine */
        $content = file_get_contents($baselinePath);
        if (false === $content) {
            throw new \RuntimeException(sprintf('Cannot get content of baseline file (%s)', $baselinePath));
        }
        $baseLine = json_decode($content, true, 512, \JSON_THROW_ON_ERROR);
    }

    /** @var Finder $baseFinder */
    $baseFinder = require __DIR__ . '/.php-cs-fixer-finder.php';

    return (new Finder())->append(excludeNotChangedFiles($baseFinder, $baseLine));
}

/**
 * @param array<string,array{ hash: int }> $baseLine
 *
 * @return string[]
 */
function excludeNotChangedFiles(Finder $finder, array $baseLine): array
{
    $paths = [];
    foreach ($finder as $file) {
        /** @var \SplFileinfo $file */
        $filePath = $file->getPathname();
        $oldHash = $baseLine[$filePath]['hash'] ?? null;
        $content = file_get_contents($filePath);
        if (false === $content) {
            throw new \RuntimeException(sprintf('Cannot get content of file: %s', $filePath));
        }
        $newHash = crc32($content ?: '');
        if ($oldHash !== $newHash) {
            $paths[] = $filePath;
        }
    }

    sort($paths);

    return $paths;
}

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules($rules)
    ->setFinder(createFinder())
    ->setCacheFile(__DIR__ . '/.php_cs.cache');
