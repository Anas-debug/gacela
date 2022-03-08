<?php

declare(strict_types=1);

use Gacela\Framework\AbstractConfigGacela;
use Gacela\Framework\Config\GacelaConfigArgs\MappingInterfacesResolver;
use GacelaTest\Feature\Framework\BindingInterfacesWithInnerDependencies\LocalConfig\Domain\Greeter\CorrectCompanyGenerator;
use GacelaTest\Feature\Framework\BindingInterfacesWithInnerDependencies\LocalConfig\Domain\Greeter\IncorrectCompanyGenerator;
use GacelaTest\Feature\Framework\BindingInterfacesWithInnerDependencies\LocalConfig\Domain\GreeterGeneratorInterface;

/**
 * This Feature-test does two things:
 *
 * - 1: Check the "globalService" variable was properly defined in the 'Gacela::bootstrap()' with the key `isWorking?`.
 *
 * - 2: Let Gacela resolve in the factory the mapping from `GreeterGeneratorInterface` to `CorrectCompanyGenerator`
 *      AND auto-resolve the class `CustomNameGenerator` from the `CorrectCompanyGenerator` constructor.
 */
return static fn () => new class () extends AbstractConfigGacela {
    public function mappingInterfaces(MappingInterfacesResolver $interfacesResolver, array $globalServices): void
    {
        $interfacesResolver->bind(GreeterGeneratorInterface::class, IncorrectCompanyGenerator::class);

        if ('yes!' === $globalServices['isWorking?']) {
            $interfacesResolver->bind(GreeterGeneratorInterface::class, CorrectCompanyGenerator::class);
        }
    }
};
