"""
Azure OpenAI Chat Completion Example

This example demonstrates how to use the Azure OpenAI service to generate
chat completions. It shows how to configure the client, send a prompt,
and process the response.

Prerequisites:
    - An Azure OpenAI resource with a deployed model
    - The openai Python package (pip install openai)
    - Environment variables set for authentication (see below)

Environment Variables:
    AZURE_OPENAI_ENDPOINT: Your Azure OpenAI resource endpoint
                           (e.g. https://<your-resource>.openai.azure.com/)
    AZURE_OPENAI_API_KEY:  Your Azure OpenAI API key
    AZURE_OPENAI_DEPLOYMENT_NAME: The name of your deployed model
                                  (e.g. gpt-4o)
    AZURE_OPENAI_API_VERSION: API version to use (default: 2024-12-01-preview)
"""

import os
import sys

from openai import AzureOpenAI


def get_env_var(name: str, default: str | None = None) -> str:
    """Retrieve a required environment variable or exit with an error."""
    value = os.environ.get(name, default)
    if value is None:
        print(f"Error: environment variable '{name}' is not set.")
        sys.exit(1)
    return value


def main() -> None:
    endpoint = get_env_var("AZURE_OPENAI_ENDPOINT")
    api_key = get_env_var("AZURE_OPENAI_API_KEY")
    deployment = get_env_var("AZURE_OPENAI_DEPLOYMENT_NAME")
    api_version = get_env_var("AZURE_OPENAI_API_VERSION", "2024-12-01-preview")

    client = AzureOpenAI(
        azure_endpoint=endpoint,
        api_key=api_key,
        api_version=api_version,
    )

    messages = [
        {
            "role": "system",
            "content": "You are a helpful assistant.",
        },
        {
            "role": "user",
            "content": "What are three benefits of using Azure OpenAI?",
        },
    ]

    print(f"Sending request to deployment '{deployment}'...\n")

    response = client.chat.completions.create(
        model=deployment,
        messages=messages,
    )

    reply = response.choices[0].message.content
    print("Response:\n")
    print(reply)
    print(f"\nTokens used — prompt: {response.usage.prompt_tokens}, "
          f"completion: {response.usage.completion_tokens}, "
          f"total: {response.usage.total_tokens}")


if __name__ == "__main__":
    main()
