<?php

declare(strict_types=1);

namespace Talav\Component\User\Model;

trait Blameable
{
    use CreatedBy;
    use UpdatedBy;
}
