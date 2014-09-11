<?php

return [
    '{url:.*}' => [
        'name' => 'view',
        'callback' => '\Modules\Pages\Controllers\PageController:view',
    ],
    '{url:.*}/comments' => [
        'name' => 'view',
        'callback' => '\Modules\Pages\Controllers\CommentController:view',
    ],
];
