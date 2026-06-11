<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        $value = Cache::remember(
            "site_setting.{$key}",
            300,
            fn () => static::where('key', $key)->value('value')
        );

        return ($value !== null && $value !== '') ? $value : $default;
    }

    public static function set(string $key, ?string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("site_setting.{$key}");
    }

    /**
     * Мөр бүрт нэг утга хадгалсан тохиргоог массив болгож буцаана.
     */
    public static function list(string $key, array $default = []): array
    {
        $value = static::get($key);

        if ($value === null) {
            return $default;
        }

        $lines = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value))));

        return $lines !== [] ? $lines : $default;
    }
}
