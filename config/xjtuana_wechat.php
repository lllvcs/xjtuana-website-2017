<?php

return [
    'template' => [
        'auth' => [
            'bind' => env('XJTUANA_WECHAT_TEMPLATE_AUTH_BIND', null),
        ],
        'order' => [
            'create' => env('XJTUANA_WECHAT_TEMPLATE_ORDER_CREATE', null),
            'receive' => env('XJTUANA_WECHAT_TEMPLATE_ORDER_RECEIVE', null),
            'complete' => env('XJTUANA_WECHAT_TEMPLATE_ORDER_COMPLETE', null),
        ]
    ]
];
