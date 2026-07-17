# aisdk/voyageai

<a href="https://github.com/phpaisdk/voyageai/actions"><img alt="GitHub Workflow Status" src="https://img.shields.io/github/actions/workflow/status/phpaisdk/voyageai/tests.yml?branch=main&label=Tests"></a>
<a href="https://packagist.org/packages/aisdk/voyageai"><img alt="Total Downloads" src="https://img.shields.io/packagist/dt/aisdk/voyageai"></a>
<a href="https://packagist.org/packages/aisdk/voyageai"><img alt="Latest Version" src="https://img.shields.io/packagist/v/aisdk/voyageai"></a>
<a href="https://packagist.org/packages/aisdk/voyageai"><img alt="License" src="https://img.shields.io/packagist/l/aisdk/voyageai"></a>
<a href="https://whyphp.dev"><img src="https://img.shields.io/badge/Why_PHP-in_2026-7A86E8?style=flat-square&labelColor=18181b" alt="Why PHP in 2026"></a>

------

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

## Documentation

- [PHP AI SDK documentation](https://phpaisdk.com/docs)
- [Voyage AI documentation](https://phpaisdk.com/docs/voyageai)

## Community

- [Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md)
- [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)
- For private security reports, email [security@phpaisdk.com](mailto:security@phpaisdk.com).
