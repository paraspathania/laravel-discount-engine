<?php

$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);

foreach($ite as $file) {
    if(pathinfo($file, PATHINFO_EXTENSION) == 'php') {
        $c = file_get_contents($file);
        
        // Match ${{
        $c = preg_replace('/\\$\{\{/', '₹{{', $c);
        
        // Match -${{ or -$10
        $c = preg_replace('/-\\$\{\{/', '-₹{{', $c);
        
        // Match '$'
        $c = str_replace('\'$\'', '\'₹\'', $c);
        
        // Match string "Save exactly $"
        $c = str_replace('Save exactly $', 'Save exactly ₹', $c);
        $c = str_replace('Min Spend: $', 'Min Spend: ₹', $c);
        $c = str_replace('Saved $', 'Saved ₹', $c);
        
        // Match >$
        $c = preg_replace('/>\\$/', '>₹', $c);
        
        // Match " $"
        $c = preg_replace('/ \\$/', ' ₹', $c);

        file_put_contents($file, $c);
    }
}
echo "Done";
