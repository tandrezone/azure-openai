<?php

declare(strict_types=1);

namespace Tandrezone\AzureOpenAI\Tests;

use InvalidArgumentException;
use OpenAI\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Tandrezone\AzureOpenAI\AzureOpenAI;

class AzureOpenAITest extends TestCase
{
    private function mockHttpClient(): ClientInterface
    {
        return $this->createMock(ClientInterface::class);
    }

    public function test_client_returns_openai_client_instance(): void
    {
        $client = AzureOpenAI::client(
            resourceName: 'my-resource',
            deploymentId: 'gpt-35-turbo',
            apiKey: 'test-api-key',
            apiVersion: '2024-02-01',
            httpClient: $this->mockHttpClient(),
        );

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_client_uses_default_api_version(): void
    {
        $client = AzureOpenAI::client(
            resourceName: 'my-resource',
            deploymentId: 'gpt-35-turbo',
            apiKey: 'test-api-key',
            httpClient: $this->mockHttpClient(),
        );

        $this->assertInstanceOf(Client::class, $client);
    }

    public function test_throws_exception_for_empty_resource_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('resourceName');

        AzureOpenAI::client(
            resourceName: '',
            deploymentId: 'gpt-35-turbo',
            apiKey: 'test-api-key',
        );
    }

    public function test_throws_exception_for_whitespace_resource_name(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('resourceName');

        AzureOpenAI::client(
            resourceName: '   ',
            deploymentId: 'gpt-35-turbo',
            apiKey: 'test-api-key',
        );
    }

    public function test_throws_exception_for_empty_deployment_id(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('deploymentId');

        AzureOpenAI::client(
            resourceName: 'my-resource',
            deploymentId: '',
            apiKey: 'test-api-key',
        );
    }

    public function test_throws_exception_for_empty_api_key(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('apiKey');

        AzureOpenAI::client(
            resourceName: 'my-resource',
            deploymentId: 'gpt-35-turbo',
            apiKey: '',
        );
    }

    public function test_throws_exception_for_empty_api_version(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('apiVersion');

        AzureOpenAI::client(
            resourceName: 'my-resource',
            deploymentId: 'gpt-35-turbo',
            apiKey: 'test-api-key',
            apiVersion: '',
        );
    }
}
