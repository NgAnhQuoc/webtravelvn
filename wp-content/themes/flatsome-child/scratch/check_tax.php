<?php
require_once('../../../wp-load.php');
$taxonomies = get_taxonomies(array('public' => true), 'objects');
echo "TAXONOMY LIST:" . PHP_EOL;
foreach($taxonomies as $tax) {
    $terms = get_terms(array('taxonomy' => $tax->name, 'hide_empty' => false));
    echo "- Name: " . $tax->name . " | Label: " . $tax->label . " | Terms: " . count($terms) . PHP_EOL;
}
