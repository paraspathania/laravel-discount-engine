<?php

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);

foreach($ite as $file) {
    if(pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $c = file_get_contents($file);
        
        // 1. REVERT ALL ₹ back to $
        $c = str_replace('₹', '$', $c);
        
        // 2. Apply correct replacements
        // Replace ${{ with ₹{{
        $c = preg_replace('/\\$\{\{/', '₹{{', $c);
        
        // Replace -${{ with -₹{{
        $c = preg_replace('/-\\$\{\{/', '-₹{{', $c);
        
        // Replace $ followed by a number with ₹ followed by the number
        $c = preg_replace('/\\$([0-9])/', '₹$1', $c);
        
        // Replace -$ followed by a number with -₹ followed by the number
        $c = preg_replace('/-\\$([0-9])/', '-₹$1', $c);
        
        // Replace '$' with '₹' (for AlpineJS text like '$' + formatted_price)
        $c = str_replace('\'$\'', '\'₹\'', $c);

        file_put_contents($file, $c);
    }
}
echo "Fixed and Replaced";
