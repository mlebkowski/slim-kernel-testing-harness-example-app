<?php

declare(strict_types=1);

namespace Acme\Application\Authorization;

enum Role {
    case Admin;
    case None;
}
