<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

trait InteractsWithCursor
{
    use Tokens\HasNavigation;
    use Tokens\HasConsume;
    use Tokens\ChecksType;
    use Tokens\MatchesOptional;
}
