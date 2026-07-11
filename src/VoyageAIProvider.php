<?php

declare(strict_types=1);

namespace AiSdk\VoyageAI;

use AiSdk\Contracts\BaseProvider;
use AiSdk\Contracts\EmbeddingModelInterface;
use AiSdk\Contracts\EmbeddingProviderInterface;
use AiSdk\VoyageAI\Models\VoyageAIEmbeddingModel;

final class VoyageAIProvider extends BaseProvider implements EmbeddingProviderInterface
{
    public function __construct(public readonly VoyageAIOptions $options) {}

    public function name(): string
    {
        return VoyageAIOptions::PROVIDER_NAME;
    }

    public function embeddingModel(string $modelId): EmbeddingModelInterface
    {
        return new VoyageAIEmbeddingModel($modelId, $this->options);
    }
}
