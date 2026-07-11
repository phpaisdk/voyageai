<?php

declare(strict_types=1);

namespace AiSdk\VoyageAI\Models;

use AiSdk\Contracts\BaseModel;
use AiSdk\Contracts\EmbeddingModelInterface;
use AiSdk\Exceptions\InvalidArgumentException;
use AiSdk\OpenAICompatible\EmbeddingRequestBuilder;
use AiSdk\OpenAICompatible\EmbeddingResponseParser;
use AiSdk\Requests\EmbeddingRequest;
use AiSdk\Responses\EmbeddingResponse;
use AiSdk\Utils\Support\Url;
use AiSdk\VoyageAI\VoyageAIOptions;

final class VoyageAIEmbeddingModel extends BaseModel implements EmbeddingModelInterface
{
    public function __construct(
        private readonly string $modelId,
        private readonly VoyageAIOptions $options,
    ) {}

    public function provider(): string
    {
        return VoyageAIOptions::PROVIDER_NAME;
    }

    public function modelId(): string
    {
        return $this->modelId;
    }

    public function generate(EmbeddingRequest $request): EmbeddingResponse
    {
        $body = EmbeddingRequestBuilder::build(
            $this->modelId,
            $this->provider(),
            $request,
            [
                'dimensionsParameter' => 'output_dimension',
                'includeEncodingFormat' => false,
            ],
        );

        if (isset($body['output_dtype']) && $body['output_dtype'] !== 'float') {
            throw new InvalidArgumentException('Voyage AI embeddings currently support only output_dtype: float.');
        }
        if (isset($body['encoding_format'])) {
            throw new InvalidArgumentException('Voyage AI embeddings do not support base64 encoding through the portable float-vector contract.');
        }

        $payload = $this->runner($this->options->sdk)->postJson(
            Url::joinPath($this->options->baseUrl, '/embeddings'),
            $body,
            $this->options->authHeaders(),
            $this->provider(),
        );

        return EmbeddingResponseParser::parse($payload, $this->provider(), count($request->inputs));
    }
}
