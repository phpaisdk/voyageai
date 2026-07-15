# aisdk/voyageai

Official Voyage AI provider for the PHP AI SDK.

## Installation

```bash
composer require aisdk/voyageai
```

## Text Embeddings

```php
use AiSdk\Generate;
use AiSdk\VoyageAI;

$result = Generate::embedding([
        'First document',
        'Second document',
    ])
    ->model(VoyageAI::model('voyage-4-large'))
    ->dimensions(512)
    ->providerOptions('voyageai', [
        'input_type' => 'document',
    ])
    ->run();

$vector = $result->output->vector;
$vectors = $result->embeddings;
```

The portable API covers Voyage's text embeddings endpoint. Multimodal, contextualized chunk embeddings, and reranking use different API contracts and are not part of this package surface yet.

## Configuration

| Variable | Description | Default |
|---|---|---|
| `VOYAGE_API_KEY` | API key for authentication | Required |
| `VOYAGE_BASE_URL` | Base URL for API requests | `https://api.voyageai.com/v1` |

```php
VoyageAI::create([
    'apiKey' => 'pa-...',
    'baseUrl' => 'https://api.voyageai.com/v1',
    'headers' => ['X-Custom-Header' => 'value'],
]);
```

## Provider-Specific Options

Voyage's documented text embedding fields pass through unchanged:

```php
$result = Generate::embedding('A search query')
    ->model(VoyageAI::model('voyage-4-large'))
    ->providerOptions('voyageai', [
        'input_type' => 'query',
        'truncation' => true,
        'output_dtype' => 'float',
    ])
    ->run();
```

`dimensions()` maps to Voyage's `output_dimension` field. Model IDs pass through unchanged so Voyage's API remains the source of truth for model availability and supported dimensions.

The portable SDK result is a float vector, so this adapter accepts Voyage's `output_dtype: float` only and rejects base64 encoding. Quantized and bit-packed output need a separate result contract rather than being mislabeled as ordinary float dimensions.

## Testing

```bash
composer test
```

## Links

- [Voyage AI Embeddings API](https://docs.voyageai.com/reference/embeddings-api)
- [Core Package](https://github.com/phpaisdk/core)
- [Project Documentation](https://github.com/phpaisdk)
