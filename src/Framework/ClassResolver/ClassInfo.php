<?php

declare(strict_types=1);

namespace Gacela\Framework\ClassResolver;

use function array_slice;
use function count;
use function get_class;
use function is_object;
use function is_string;

final class ClassInfo implements ClassInfoInterface
{
    public const MODULE_NAME_ANONYMOUS = 'module-name@anonymous';

    /** @var array<string,array<string,self>> */
    private static array $callerClassCache;

    private string $callerModuleNamespace;
    private string $callerModuleName;
    private string $cacheKey;
    private string $resolvableType;

    public function __construct(
        string $callerModuleNamespace,
        string $callerModuleName,
        string $cacheKey,
        string $resolvableType = ''
    ) {
        $this->callerModuleNamespace = $callerModuleNamespace;
        $this->callerModuleName = $callerModuleName;
        $this->cacheKey = $cacheKey;
        $this->resolvableType = $resolvableType;
    }

    /**
     * @param object|class-string $caller
     */
    public static function from($caller, string $resolvableType = ''): self
    {
        if (is_object($caller)) {
            return self::fromObject($caller, $resolvableType);
        }

        return self::fromString($caller, $resolvableType);
    }

    public function getModuleNamespace(): string
    {
        return $this->callerModuleNamespace;
    }

    public function getModuleName(): string
    {
        return $this->callerModuleName;
    }

    public function getCacheKey(): string
    {
        return $this->cacheKey;
    }

    public function getResolvableType(): string
    {
        return $this->resolvableType;
    }

    public function toString(): string
    {
        return sprintf(
            'ClassInfo{callerModuleNamespace:"%s", callerModuleName:"%s", resolvableType:"%s", cacheKey:"%s"}',
            $this->callerModuleNamespace,
            $this->callerModuleName,
            $this->resolvableType,
            $this->cacheKey,
        );
    }

    private static function fromObject(object $callerObject, string $resolvableType): self
    {
        $callerClass = get_class($callerObject);

        return self::fromString($callerClass, $resolvableType);
    }

    private static function fromString(string $callerClass, string $resolvableType): self
    {
        if (isset(self::$callerClassCache[$callerClass][$resolvableType])) {
            return self::$callerClassCache[$callerClass][$resolvableType];
        }
        /** @var list<string> $callerClassParts */
        $callerClassParts = explode('\\', ltrim($callerClass, '\\'));
        $lastCallerClassPart = end($callerClassParts);
        $filepath = is_string($lastCallerClassPart) ? $lastCallerClassPart : '';
        $filename = self::normalizeFilename($filepath);

        if (strpos($filepath, 'anonymous') !== false) {
            $callerClassParts = [
                self::MODULE_NAME_ANONYMOUS . '\\' . $filename,
                $filepath,
            ];
        }

        $callerFullNamespace = implode('\\', array_slice($callerClassParts, 0, count($callerClassParts) - 1));

        $callerModuleNamespace = substr($callerFullNamespace, 0, (int)strrpos($callerFullNamespace, '\\'));
        $callerModuleName = $callerClassParts[count($callerClassParts) - 2] ?? '';
        $cacheKey = GlobalKey::fromClassName(sprintf('\\%s\\%s', $callerFullNamespace, $resolvableType));

        $self = new self($callerModuleNamespace, $callerModuleName, $cacheKey, $resolvableType);
        self::$callerClassCache[$callerClass][$resolvableType] = $self;

        return $self;
    }

    private static function normalizeFilename(string $filepath): string
    {
        $filename = basename($filepath);
        $filename = substr($filename, 0, (int)strpos($filename, ':'));

        if (false === ($pos = strpos($filename, '.'))) {
            return $filename;
        }

        return substr($filename, 0, $pos);
    }
}
