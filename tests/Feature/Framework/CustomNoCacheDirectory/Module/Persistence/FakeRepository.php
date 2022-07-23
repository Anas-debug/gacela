<?php

declare(strict_types=1);

namespace GacelaTest\Feature\Framework\CustomNoCacheDirectory\Module\Persistence;

final class FakeRepository
{
    public function findName(): string
    {
        return 'name';
    }
}
