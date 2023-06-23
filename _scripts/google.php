<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';


use Google\Cloud\Language\Annotation;

$annotation = new Annotation([
    'score' => 1,
    'documentSentiment' => [

    ]
]);

if ($annotation->sentiment() > 0) {
    echo "This is a positive message.\n";
}

?>
