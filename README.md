# Usage
How to use: Open index.html in the PHP environment and enter the prompt text to create the image.

Cloudflare's Workers AI has 100,000 free request counts, which is completely sufficient for personal use.

# To obtain AccountID and ApiToken:
Log in to Cloudflare (if you don't have an account, you need to register one) -> Go to Workers & Pages (you can find AccountID on the right side) -> Under Account ID, click on Manage API tokens -> Then click on Create Token -> Choose Workers AI (Beta) -> Create to obtain the ApiToken. Make sure to save the AccountID and ApiToken directly to avoid forgetting them and needing to recreate them.

![120240307162110](/images/120240307162110.png)

Test site：https://text-img.freetu.top/

Cloudflare：https://dash.cloudflare.com/

Cloudflare Workers AI docs：[https://developers.cloudflare.com/workers-ai/models/text-to-image/](https://developers.cloudflare.com/workers-ai/models/#text-to-image)

# php-cloudflare-text-to-image
Cloudflare Workers AI Text to Image PHP code Api and HTML.
