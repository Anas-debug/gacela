<?php

declare(strict_types=1);

namespace GacelaTest\Feature\Framework\CustomServiceAware\Module;

use Gacela\Framework\AbstractFacade;

final class Facade extends AbstractFacade
{
    public function sayHi(): string
    {
        return 'Hi';
    }
}
