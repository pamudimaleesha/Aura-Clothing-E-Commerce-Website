<?php
/**
 * Helper functions for Aura Clothing
 */

// Function to generate stylish colors for clothing categories
if (!function_exists('generateCategoryColor')) {

    function generateCategoryColor($categoryName) {

        // Generate unique hash
        $hash = crc32($categoryName);

        // Fashion-style color palette
        $colors = [

            // Background Color , Text Color

            ['#000000', '#ffffff'], // Black
            ['#050505', '#ffffff'], // Dark Gray
            ['#6A1B9A', '#ffffff'], // Purple
            ['#E91E63', '#ffffff'], // Pink
            ['#C2185B', '#ffffff'], // Rose
            ['#3F51B5', '#ffffff'], // Indigo
            ['#009688', '#ffffff'], // Teal
            ['#795548', '#ffffff'], // Brown
            ['#f5c93b', '#000000'], // Gold
            ['#df1f3c', '#ffffff'], // Rose Gold
            ['#F5F5F5', '#000000'], // Light White
            ['#48a8d8', '#ffffff'], // Blue Gray
            ['#FF5722', '#ffffff'], // Deep Orange
            ['#8BC34A', '#000000'], // Light Green
            ['#795656', '#ffffff'], // Gray

        ];

        // Pick consistent color
        $index = abs($hash) % count($colors);

        return $colors[$index];
    }
}
?>