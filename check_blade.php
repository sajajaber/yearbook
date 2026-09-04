<?php
$file = 'resources/views/public/yearbook/pdf/book.blade.php';
$content = file_get_contents($file);

$directives = [
    '@foreach' => '@endforeach',
    '@forelse' => '@endforelse',
    '@if'      => '@endif',
    '@php'     => '@endphp',
];

foreach ($directives as $open => $close) {
    // count occurrences of the exact directive (word-boundary-ish check)
    $openCount = preg_match_all('/' . preg_quote($open, '/') . '\s*[\(\s]/', $content);
    $closeCount = preg_match_all('/' . preg_quote($close, '/') . '\b/', $content);
    echo "$open: $openCount   $close: $closeCount" . ($openCount !== $closeCount ? "  <-- MISMATCH" : "") . PHP_EOL;
}

echo PHP_EOL . "Total lines: " . count(explode("\n", $content)) . PHP_EOL;
