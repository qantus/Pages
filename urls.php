<?php

return [
    '{url:.*}' => [
        'name' => 'view',
        'callback' => '\Modules\Pages\Controllers\PageController:view',
    ],
];
