<?php

/**
 * Azure OpenAI – Excel Product Description Generator
 *
 * This example reads product data from an Excel file and uses Azure OpenAI to
 * generate a marketing description for each product based on its attributes.
 *
 * Prerequisites:
 *     - PHP 8.2+
 *     - Composer dependencies installed (composer install)
 *     - A .env file in the examples/ directory (copy .env.example and fill in your values)
 *
 * Usage:
 *     php examples/excel_product_description.php examples/sample_products.xlsx
 *
 *     Optionally write results to a new Excel file:
 *     php examples/excel_product_description.php examples/sample_products.xlsx --output descriptions.xlsx
 */

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tandrezone\AzureOpenAI\AzureOpenAI;

// ── Parse CLI arguments ──────────────────────────────────────────────

$options  = getopt('o:', ['output:']);
$args     = array_values(array_filter($argv, fn (string $arg) => !str_starts_with($arg, '-') && $arg !== $argv[0]));

// Remove values that follow --output / -o from positional args
$outputFile = $options['output'] ?? $options['o'] ?? null;
if ($outputFile !== null) {
    $args = array_filter($args, fn (string $arg) => $arg !== $outputFile);
    $args = array_values($args);
}

if (count($args) === 0) {
    fprintf(STDERR, "Usage: php %s <excel-file> [--output <output-file>]\n", $argv[0]);
    exit(1);
}

$excelFile = $args[0];

if (!file_exists($excelFile)) {
    fprintf(STDERR, "Error: file '%s' not found.\n", $excelFile);
    exit(1);
}

// ── Load .env ────────────────────────────────────────────────────────

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

// ── Read Excel data ──────────────────────────────────────────────────

echo "Reading products from '{$excelFile}'...\n\n";

$spreadsheet = IOFactory::load($excelFile);
$worksheet   = $spreadsheet->getActiveSheet();
$rows        = $worksheet->toArray();

if (count($rows) < 2) {
    fprintf(STDERR, "Error: the Excel file must have a header row and at least one data row.\n");
    exit(1);
}

$headerRow = $rows[0];
$headerIndices = [];
$headers = [];

foreach ($headerRow as $i => $h) {
    if ($h !== null && trim((string) $h) !== '') {
        $headerIndices[] = $i;
        $headers[] = trim((string) $h);
    }
}

$products = [];

foreach (array_slice($rows, 1) as $row) {
    $product = [];
    foreach ($headerIndices as $j => $i) {
        $product[$headers[$j]] = isset($row[$i]) ? trim((string) $row[$i]) : '';
    }
    if (array_filter($product)) {
        $products[] = $product;
    }
}

echo sprintf("Found %d product(s).\n\n", count($products));

// ── Generate descriptions ────────────────────────────────────────────

$descriptions = [];
$total        = count($products);

foreach ($products as $index => $product) {
    $name = $product['Product Name'] ?? array_values($product)[0] ?? 'Product ' . ($index + 1);
    echo sprintf("[%d/%d] Generating description for '%s'...\n", $index + 1, $total, $name);

    $filtered = array_filter($product, fn (string $value) => $value !== '');
    $attributes = implode("\n", array_map(
        fn (string $key) => "- {$key}: {$filtered[$key]}",
        array_keys($filtered),
    ));

    $prompt = "Based on the following product attributes, write a short, compelling "
        . "marketing description (2-3 sentences) for this product. "
        . "Highlight key selling points and appeal to the target audience.\n\n"
        . "Product attributes:\n{$attributes}";

    $response = $client->chat()->create([
        'model'       => $deploymentId,
        'messages'    => [
            [
                'role'    => 'system',
                'content' => 'You are a professional copywriter specializing in product descriptions. Write concise and engaging marketing copy.',
            ],
            [
                'role'    => 'user',
                'content' => $prompt,
            ],
        ],
        'temperature' => 0.7,
        'max_tokens'  => 256,
    ]);

    $description    = trim($response->choices[0]->message->content ?? '');
    $descriptions[] = $description;
    echo "  → {$description}\n\n";
}

// ── Save results ─────────────────────────────────────────────────────

if ($outputFile !== null) {
    $output    = new Spreadsheet();
    $outSheet  = $output->getActiveSheet();
    $outSheet->setTitle('Product Descriptions');

    $outHeaders = array_merge($headers, ['Generated Description']);
    $outSheet->fromArray($outHeaders, null, 'A1');

    foreach ($products as $i => $product) {
        $row = array_merge(array_values($product), [$descriptions[$i]]);
        $outSheet->fromArray($row, null, 'A' . ($i + 2));
    }

    // Auto-fit column widths
    foreach (range('A', $outSheet->getHighestColumn()) as $col) {
        $outSheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($output);
    $writer->save($outputFile);
    echo "Results saved to '{$outputFile}'.\n";
}
