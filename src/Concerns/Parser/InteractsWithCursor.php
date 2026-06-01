<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser;

trait InteractsWithCursor
{
    use NavigatesTokens;
    use ChecksTokenType;
    use ConsumesExpectedToken;
    use MatchesOptionalToken;
}
