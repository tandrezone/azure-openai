# azure-openai

A collection of examples for working with the [Azure OpenAI Service](https://learn.microsoft.com/en-us/azure/ai-services/openai/).

## Prerequisites

- Python 3.10+
- An Azure subscription with an [Azure OpenAI resource](https://learn.microsoft.com/en-us/azure/ai-services/openai/how-to/create-resource)
- A deployed model (e.g. `gpt-4o`)

## Quick Start

1. **Install dependencies**

   ```bash
   pip install -r examples/requirements.txt
   ```

2. **Set environment variables**

   ```bash
   export AZURE_OPENAI_ENDPOINT="https://<your-resource>.openai.azure.com/"
   export AZURE_OPENAI_API_KEY="<your-api-key>"
   export AZURE_OPENAI_DEPLOYMENT_NAME="<your-deployment-name>"
   ```

3. **Run the example**

   ```bash
   python examples/chat_completion.py
   ```

## Examples

| Example | Description |
| --- | --- |
| [Chat Completion](examples/chat_completion.py) | Send a chat prompt to Azure OpenAI and display the response |
| [Excel Product Description](examples/excel_product_description.py) | Read product data from an Excel file and generate marketing descriptions |

### Chat Completion

```bash
python examples/chat_completion.py
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
python examples/excel_product_description.py examples/sample_products.xlsx

# Save results to a new Excel file
python examples/excel_product_description.py examples/sample_products.xlsx --output descriptions.xlsx
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
    finish. Durable, lightweight, and 100 % eco-friendly — the perfect
    companion for conscious consumers on the go.
```

## License

This project is provided as-is for educational purposes.
