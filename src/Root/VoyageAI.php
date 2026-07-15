<?php

declare(strict_types=1);

namespace AiSdk;

use AiSdk\Contracts\Model;
use AiSdk\VoyageAI\VoyageAIOptions;
use AiSdk\VoyageAI\VoyageAIProvider;

/**
 * Friendly facade for the Voyage AI provider.
 *
 *   $model = VoyageAI::model('voyage-4-large');
 */
final class VoyageAI
{
    private static ?VoyageAIProvider $default = null;

    /**
     * @param  array<string, mixed>  $config
     */
    public static function create(array $config = []): VoyageAIProvider
    {
        return self::$default = new VoyageAIProvider(VoyageAIOptions::fromArray($config));
    }

    public static function default(): VoyageAIProvider
    {
        return self::$default ??= self::create();
    }

    public static function reset(): void
    {
        self::$default = null;
    }

    public static function model(string $modelId): Model
    {
        return self::default()->model($modelId);
    }
}
