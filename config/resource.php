<?php

use \App\Repository\QrRepository;
use \App\Models\Qr;
use \App\Resources\Qr as QrTransformer;

return [
    'repositories' => [
        'v1' => [
            'qrs' => [
                'repo' => QrRepository::class,
                'model' => Qr::class,
                'transformer' => QrTransformer::class,
            ],
        ]
    ]
];
