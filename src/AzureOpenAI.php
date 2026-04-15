<?php

declare(strict_types=1);

namespace Tandrezone\AzureOpenAI;

use InvalidArgumentException;
use OpenAI;
use OpenAI\Client;
use Psr\Http\Client\ClientInterface;

/**
 * Factory class for creating an OpenAI client configured for Azure OpenAI Service.
 *
 * Azure OpenAI uses a resource-scoped base URI, an API key header, and an
 * api-version query parameter instead of the standard OpenAI endpoint.
 */
final class AzureOpenAI
{
    /**
     * Create an OpenAI client configured for Azure OpenAI Service.
     *
     * @param  string               $resourceName   Azure OpenAI resource name (subdomain).
     * @param  string               $deploymentId   The deployment ID (model deployment name).
     * @param  string               $apiKey         The Azure OpenAI API key.
     * @param  string               $apiVersion     The Azure OpenAI REST API version (e.g. '2024-02-01').
     * @param  ClientInterface|null $httpClient     Optional PSR-18 HTTP client; auto-discovered when null.
     *
     * @throws InvalidArgumentException When any required parameter is empty.
     */
    public static function client(
        string $resourceName,
        string $deploymentId,
        string $apiKey,
        string $apiVersion = '2024-02-01',
        ?ClientInterface $httpClient = null,
    ): Client {
        self::validateParameters($resourceName, $deploymentId, $apiKey, $apiVersion);

        $baseUri = sprintf(
            '%s.openai.azure.com/openai/deployments/%s',
            $resourceName,
            $deploymentId
        );

        $factory = OpenAI::factory()
            ->withBaseUri($baseUri)
            ->withHttpHeader('api-key', $apiKey)
            ->withQueryParam('api-version', $apiVersion);

        if ($httpClient !== null) {
            $factory = $factory->withHttpClient($httpClient);
        }

        return $factory->make();
    }

    /**
     * Validate that all required parameters are non-empty strings.
     *
     * @throws InvalidArgumentException
     */
    private static function validateParameters(
        string $resourceName,
        string $deploymentId,
        string $apiKey,
        string $apiVersion,
    ): void {
        $params = [
            'resourceName' => $resourceName,
            'deploymentId' => $deploymentId,
            'apiKey'       => $apiKey,
            'apiVersion'   => $apiVersion,
        ];

        foreach ($params as $name => $value) {
            if (trim($value) === '') {
                throw new InvalidArgumentException(
                    sprintf('The parameter "%s" must not be empty.', $name)
                );
            }
        }
    }
}
