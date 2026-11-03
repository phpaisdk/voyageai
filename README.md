# PHP AI SDK — Voyage AI

Text embeddings with Voyage AI for the framework-agnostic [PHP AI SDK](https://phpaisdk.com).

## Installation

```bash
composer require aisdk/voyageai
```

Set `VOYAGE_API_KEY` in your environment.

## Usage

```php
use AiSdk\Generate;
use AiSdk\VoyageAI;

$result = Generate::embedding('A guide to building PHP applications.')
    ->model(VoyageAI::model('voyage-4-large'))
    ->providerOptions('voyageai', ['input_type' => 'document'])
    ->run();

$vector = $result->output->vector;
```

## Documentation

[Read the documentation](https://phpaisdk.com/docs/0.x/providers/voyageai) for configuration, supported features, and more examples.

[Contributing](https://github.com/phpaisdk/.github/blob/main/CONTRIBUTING.md) · [Support](https://github.com/phpaisdk/.github/blob/main/SUPPORT.md)

## License

The MIT License (MIT). See [LICENSE](LICENSE) for details.
