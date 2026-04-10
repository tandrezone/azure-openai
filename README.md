# azure-openai

A PHP package to interact with the [Azure OpenAI Service](https://learn.microsoft.com/en-us/azure/ai-services/openai/) using [openai-php/client](https://github.com/openai-php/client).

## Requirements

- PHP 8.2+
- An [Azure OpenAI resource](https://portal.azure.com/) with a deployed model

## Installation

```bash
composer require tandrezone/azure-openai
```

## Usage

### Creating a client

```php
use Tandrezone\AzureOpenAI\AzureOpenAI;

$client = AzureOpenAI::client(
    resourceName: 'your-resource-name',    // Azure resource subdomain
    deploymentId: 'gpt-35-turbo',          // Your deployed model name
    apiKey:       getenv('AZURE_OPENAI_API_KEY'),
    apiVersion:   '2024-02-01',            // Optional, defaults to '2024-02-01'
);
```

This configures the underlying `openai-php/client` to point at:

```
https://{resourceName}.openai.azure.com/openai/deployments/{deploymentId}/
```

…and adds the required `api-key` header and `api-version` query parameter automatically.

### Chat completions

Since the deployment ID is already embedded in the base URI, you **do not** need to specify a model in API calls:

```php
$response = $client->chat()->create([
    'messages' => [
        ['role' => 'user', 'content' => 'Hello, world!'],
    ],
]);

echo $response->choices[0]->message->content;
```

### Embeddings

```php
$response = $client->embeddings()->create([
    'input' => 'The food was delicious.',
]);

echo $response->embeddings[0]->embedding[0]; // float
```

### Completions (legacy)

```php
$response = $client->completions()->create([
    'prompt'     => 'PHP is',
    'max_tokens' => 100,
]);

echo $response->choices[0]->text;
```

### Using a custom HTTP client

Pass any [PSR-18](https://www.php-fig.org/psr/psr-18/) compatible HTTP client as the fifth argument:

```php
use GuzzleHttp\Client as GuzzleClient;

$client = AzureOpenAI::client(
    resourceName: 'your-resource-name',
    deploymentId: 'gpt-35-turbo',
    apiKey:       getenv('AZURE_OPENAI_API_KEY'),
    apiVersion:   '2024-02-01',
    httpClient:   new GuzzleClient(),
);
```

## Configuration reference

| Parameter      | Type                  | Required | Default        | Description                                        |
|----------------|-----------------------|----------|----------------|----------------------------------------------------|
| `resourceName` | `string`              | ✅        | —              | Azure OpenAI resource name (subdomain)             |
| `deploymentId` | `string`              | ✅        | —              | Model deployment name                              |
| `apiKey`       | `string`              | ✅        | —              | Azure OpenAI API key                               |
| `apiVersion`   | `string`              | ❌        | `2024-02-01`   | Azure OpenAI REST API version                      |
| `httpClient`   | `ClientInterface\|null` | ❌      | `null` (auto)  | PSR-18 HTTP client; auto-discovered when `null`    |

## Testing

```bash
composer test
```

## License

MIT
