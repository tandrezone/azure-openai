<?php

/**
 * Azure OpenAI Chat Completion Example
 *
 * This example demonstrates how to use the Tandrezone\AzureOpenAI package to
 * generate chat completions via the Azure OpenAI Service.
 *
 * Prerequisites:
 *     - PHP 8.2+
 *     - Composer dependencies installed (composer install)
 *     - A .env file in the examples/ directory (copy .env.example and fill in your values)
 *
 * Usage:
 *     php examples/chat_completion.php
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Tandrezone\AzureOpenAI\AzureOpenAI;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required([
    'AZURE_OPENAI_RESOURCE_NAME',
    'AZURE_OPENAI_DEPLOYMENT_ID',
    'AZURE_OPENAI_API_KEY',
]);

$resourceName = $_ENV['AZURE_OPENAI_RESOURCE_NAME'];
$deploymentId = $_ENV['AZURE_OPENAI_DEPLOYMENT_ID'];
$apiKey       = $_ENV['AZURE_OPENAI_API_KEY'];
$apiVersion   = $_ENV['AZURE_OPENAI_API_VERSION'] ?? '2024-02-01';

$client = AzureOpenAI::client(
    resourceName: $resourceName,
    deploymentId: $deploymentId,
    apiKey: $apiKey,
    apiVersion: $apiVersion,
);

echo "Sending request to deployment '{$deploymentId}'...\n\n";

$response = $client->chat()->create([
    'model'    => $deploymentId,
    'messages' => [
        ['role' => 'system', 'content' => 'You are a helpful assistant.'],
        ['role' => 'user',   'content' => 'What are three benefits of using Azure OpenAI?'],
    ],
]);

$reply = $response->choices[0]->message->content;
echo "Response:\n\n";
echo $reply . "\n\n";
echo sprintf(
    "Tokens used — prompt: %d, completion: %d, total: %d\n",
    $response->usage->promptTokens,
    $response->usage->completionTokens,
    $response->usage->totalTokens,
);
