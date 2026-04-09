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

## Example Output

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

## License

This project is provided as-is for educational purposes.