<?php

declare(strict_types = 1);

namespace Phortugol\Contracts\Parser;

use Phortugol\Contracts\Node;
use Phortugol\Parser\Parser;

interface Declaration
{
    public function __invoke(Parser $parser): Node;
}
