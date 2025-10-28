<?php
// Helper: load React CSS files and print them for base.twig inclusion
$reactIndex = __DIR__ . '/../..//src/index.css';
$reactGlobals = __DIR__ . '/../..//src/styles/globals.css';

$css = '';
if (file_exists($reactIndex)) {
    $css .= file_get_contents($reactIndex) . "\n";
}
if (file_exists($reactGlobals)) {
    $css .= file_get_contents($reactGlobals) . "\n";
}

file_put_contents(__DIR__ . '/../templates/_react_css.css', $css);
echo "Wrote templates/_react_css.css\n";
