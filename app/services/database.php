<?php

declare(strict_types=1);

return [
    PDO::class => fn () => new PDO('sqlite::memory:'),
];
