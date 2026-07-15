<?php

declare(strict_types=1);

use AiSdk\Generate;
use AiSdk\Support\Sdk;
use AiSdk\VoyageAI;
use AiSdk\VoyageAI\Tests\Fakes\FakeHttpClient;
use Nyholm\Psr7\Factory\Psr17Factory;

afterEach(function () {
    Generate::reset();
    VoyageAI::reset();
});

it('generates Voyage AI text embeddings', function () {
    $client = new FakeHttpClient(200, json_encode([
        'object' => 'list',
        'model' => 'voyage-4-large',
        'data' => [
            ['object' => 'embedding', 'index' => 0, 'embedding' => [0.1, 0.2]],
            ['object' => 'embedding', 'index' => 1, 'embedding' => [0.3, 0.4]],
        ],
        'usage' => ['total_tokens' => 11],
    ]));
    $factory = new Psr17Factory();
    Generate::configure(new Sdk($client, $factory, $factory));
    VoyageAI::create(['apiKey' => 'voyage-test']);

    $result = Generate::embedding(['First document', 'Second document'])
        ->model(VoyageAI::model('voyage-4-large'))
        ->dimensions(512)
        ->providerOptions('voyageai', [
            'input_type' => 'document',
            'truncation' => false,
        ])
        ->run();

    expect($result->output->vector)->toBe([0.1, 0.2])
        ->and($result->embeddings[1]->vector)->toBe([0.3, 0.4])
        ->and($result->usage->inputTokens)->toBe(11)
        ->and($result->usage->totalTokens)->toBe(11)
        ->and($result->providerMetadata['voyageai']['model'])->toBe('voyage-4-large')
        ->and((string) $client->lastRequest?->getUri())->toBe('https://api.voyageai.com/v1/embeddings')
        ->and($client->lastRequest?->getHeaderLine('Authorization'))->toBe('Bearer voyage-test')
        ->and($client->sentBody())->toBe([
            'model' => 'voyage-4-large',
            'input' => ['First document', 'Second document'],
            'output_dimension' => 512,
            'input_type' => 'document',
            'truncation' => false,
        ]);
});

it('accepts opaque Voyage AI embedding model ids', function () {
    VoyageAI::create(['apiKey' => 'voyage-test']);

    expect(VoyageAI::model('future-voyage-model')->modelId())->toBe('future-voyage-model');
});

it('rejects quantized Voyage AI output before sending a request', function () {
    VoyageAI::create(['apiKey' => 'voyage-test']);

    Generate::embedding('A document')
        ->model(VoyageAI::model('voyage-4-large'))
        ->providerOptions('voyageai', ['output_dtype' => 'binary'])
        ->run();
})->throws(\AiSdk\Exceptions\InvalidArgumentException::class, 'only output_dtype: float');

it('rejects base64 Voyage AI output before sending a request', function () {
    VoyageAI::create(['apiKey' => 'voyage-test']);

    Generate::embedding('A document')
        ->model(VoyageAI::model('voyage-4-large'))
        ->providerOptions('voyageai', ['encoding_format' => 'base64'])
        ->run();
})->throws(\AiSdk\Exceptions\InvalidArgumentException::class, 'do not support base64');
