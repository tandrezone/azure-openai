# azure-openai

A PHP package to interact with the [Azure OpenAI Service](https://learn.microsoft.com/en-us/azure/ai-services/openai/) using [openai-php/client](https://github.com/openai-php/client).

## Prerequisites

- PHP 8.2+
- Composer
- An Azure subscription with an [Azure OpenAI resource](https://learn.microsoft.com/en-us/azure/ai-services/openai/how-to/create-resource)
- A deployed model (e.g. `gpt-4o`)

## Installation

```bash
composer require tandrezone/azure-openai
```

## Quick Start

1. **Install dependencies**

   ```bash
   composer install
   ```

2. **Configure environment variables**

   Copy the example `.env` file and fill in your Azure OpenAI credentials:

   ```bash
   cp examples/.env.example examples/.env
   ```

   Edit `examples/.env` with your values:

   ```dotenv
   AZURE_OPENAI_RESOURCE_NAME=your-resource-name
   AZURE_OPENAI_DEPLOYMENT_ID=gpt-4o
   AZURE_OPENAI_API_KEY=your-api-key
   AZURE_OPENAI_API_VERSION=2024-02-01
   ```

3. **Run the example**

   ```bash
   php examples/chat_completion.php
   ```

## Examples

| Example | Description |
| --- | --- |
| [Chat Completion](examples/chat_completion.php) | Send a chat prompt to Azure OpenAI and display the response |
| [Excel Product Description](examples/excel_product_description.php) | Read product data from an Excel file and generate marketing descriptions |

### Chat Completion

```bash
php examples/chat_completion.php
```

#### Example Output

```
Sending request to deployment 'gpt-4o'...

Response:

1. **Enterprise-Grade Security** – Azure OpenAI runs within your Azure
   subscription, giving you full control over networking, access, and
   data residency.

2. **Responsible AI** – Built-in content filtering and abuse monitoring
   help you deploy AI responsibly.

3. **Seamless Integration** – Tight integration with other Azure services
   (e.g. Azure AI Search, Azure Functions) makes it easy to build
   end-to-end solutions.

Tokens used — prompt: 26, completion: 95, total: 121
```

### Excel Product Description

Upload an Excel file containing product attributes (name, category, price,
material, etc.) and the script generates a marketing description for each
product using Azure OpenAI.

A [sample Excel file](examples/sample_products.xlsx) is included to get
started quickly.

```bash
# Print descriptions to the console
php examples/excel_product_description.php examples/sample_products.xlsx

# Save results to a new Excel file
php examples/excel_product_description.php examples/sample_products.xlsx --output descriptions.xlsx
```

#### Example Output

```
Reading products from 'examples/sample_products.xlsx'...

Found 5 product(s).

[1/5] Generating description for 'UltraComfort Running Shoes'...
  → Engineered for peak performance, the UltraComfort Running Shoes
    feature a lightweight mesh-and-rubber build that keeps you moving
    mile after mile. At just 0.3 kg, these black-and-red kicks deliver
    breathable comfort and responsive cushioning for every stride.

[2/5] Generating description for 'EcoBreeze Water Bottle'...
  → Stay hydrated and help the planet with the EcoBreeze Water Bottle,
    crafted from recycled stainless steel in a stunning ocean-blue
    finish. Durable, lightweight, and 100% eco-friendly — the perfect
    companion for conscious consumers on the go.
```

## License

This project is provided as-is for educational purposes.
