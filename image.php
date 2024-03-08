<?php

// If there is a cross-domain issue, please open it.

//header('Access-Control-Allow-Origin: *');
//header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
//header('Access-Control-Max-Age: 86400');
//header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding');
//if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
//    exit;
//}

/**
 * Generate Images
 * Developer：XiaoZhaiyuan
 * Github：https://github.com/xiaozhaiyuan/php-cloudflare-text-to-image
 * @param $prompt
 * @param $accountId
 * @param $apiToken
 * @param $models
 * @return array|string[]
 */
function generateImage($prompt, $accountId, $apiToken, $models)
{
    $numSteps = 20;
    $apiUrl = "https://api.cloudflare.com/client/v4/accounts/{$accountId}/ai/run/@cf/{$models}";
    $headers = ["Authorization: Bearer {$apiToken}", "Content-Type: application/json"];

    $jsonBody = json_encode(['prompt' => $prompt, 'num_steps' => $numSteps]);

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
        $path = 'images/' . $imageName;
        file_put_contents($path, $result);

        return ['status' => 'success', 'url' => '/' . $path];
    } else {
        return ['status' => 'error', 'message' => 'Invalid response type or error during image retrieval.'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prompt = isset($_POST['prompt']) ? $_POST['prompt'] : 'default prompt';
    $accountId = isset($_POST['accountId']) ? $_POST['accountId'] : 'default accountId';
    $apiToken = isset($_POST['apiToken']) ? $_POST['apiToken'] : 'default apiToken';
    $models = isset($_POST['models']) ? $_POST['models'] : 'default models';
    header('Content-Type: application/json');
    echo json_encode(generateImage($prompt, $accountId, $apiToken, $models));
} else {
    echo 'This page expects a POST request.';
}
