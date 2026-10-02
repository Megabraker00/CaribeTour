<?php

namespace App\Models\Concerns;

use RuntimeException;

trait ResolvesLookupCode
{
    /**
     * @var array<string, int>
     */
    private static array $lookupIdCache = [];

    abstract protected static function ownerColumn(): string;

    public static function idFor(string $owner, string $slug): int
    {
        $key = static::class.'|'.$owner.'|'.$slug;
        if (isset(self::$lookupIdCache[$key])) {
            return self::$lookupIdCache[$key];
        }

        $id = static::query()
            ->where(static::ownerColumn(), $owner)
            ->where('slug', $slug)
            ->value('id');

        if ($id === null) {
            throw new RuntimeException(sprintf(
                'No existe %s con slug [%s] para %s.',
                class_basename(static::class),
                $slug,
                $owner
            ));
        }

        return self::$lookupIdCache[$key] = (int) $id;
    }

    public static function forgetLookupCache(): void
    {
        self::$lookupIdCache = [];
    }

    public function isSystem(): bool
    {
        return (bool) $this->is_system;
    }
}
