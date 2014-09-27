<?php

return [
    '{url:.*}/c/send' => [
        'name' => 'comment_send',
        'callback' => '\Modules\Pages\Controllers\CommentController:save',
    ],
    '{url:.*}/c/' => [
        'name' => 'comment_list',
        'callback' => '\Modules\Pages\Controllers\CommentController:view',
    ],
    '{url:.*}' => [
        'name' => 'view',
        'callback' => '\Modules\Pages\Controllers\PageController:view',
    ],
];
