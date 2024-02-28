<?php
/**
 * Generate Images
 * Developer：XiaoZhaiyuan
 * Url：https://github.com/xiaozhaiyuan/php-cloudflare-text-to-image
 * @param $prompt
 * @return array|string[]
 */
function generateImage($prompt)
{
    // You cloudflare accountId ，If you are not sure, please refer to README.
    $accountId = "xxx";

    // You cloudflare apiToken ，If you are not sure, please refer to README.
    $apiToken = "xxx";

    $numSteps = 20; // default 20

    // Cloudflare Api url
    $apiUrl = "https://api.cloudflare.com/client/v4/accounts/{$accountId}/ai/run/@cf/stabilityai/stable-diffusion-xl-base-1.0";

    $headers = [
        "Authorization: Bearer {$apiToken}",
        "Content-Type: application/json"
    ];

    $jsonBody = json_encode([
        'prompt' => $prompt,
        'num_steps' => $numSteps
    ]);

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
    $result = curl_exec($ch);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);

    if (curl_errno($ch)) {
        return ['status' => 'error', 'message' => curl_error($ch)];
    }

    if ($result !== false && $contentType === "image/png") {
        $imageName = uniqid('image_', true) . '.png';
        $path = 'images/' . $imageName; // Make sure there is an 'images' folder in the root directory of the server and that it has write permissions
        file_put_contents($path, $result);

        return ['status' => 'success', 'url' => '/' . $path];
    } else {
        return ['status' => 'error', 'message' => 'Invalid response type or error during image retrieval.'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = isset($_POST['prompt']) ? $_POST['prompt'] : 'default prompt';

    // Execute the function and encode the response as JSON
    header('Content-Type: application/json');
    echo json_encode(generateImage($prompt));
} else {
    echo 'This page expects a POST request.';
}