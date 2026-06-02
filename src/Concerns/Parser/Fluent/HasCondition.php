<?php

declare(strict_types = 1);

namespace Phortugol\Concerns\Parser\Fluent;

trait HasCondition
{
    use CanBeTrue;
    use CanBeFalse;
    use CanBeLiteral;
    use CanBeVariable;
    use CanWhen;
}
