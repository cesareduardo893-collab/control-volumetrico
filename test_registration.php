<?php
// Test script for registration API endpoint
require_once 'vendor/autoload.php';

// Test data
$testData = [
    'identificacion' => 'TEST' . time(),
    'nombres' => 'Test',
    'apellidos' => 'User',
    'email' => 'test' . time() . '@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

// API URL (update this with your actual API URL)
$apiUrl = 'http://localhost:8000/api/register';

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($testData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Process response
$responseData = json_decode($response, true);

echo "HTTP Status Code: $httpCode\n";
echo "Response:\n";
print_r($responseData);

if ($httpCode === 200) {
    echo "\n=== REGISTRATION SUCCESSFUL ===\n";
    echo "User ID: " . ($responseData['user']['id'] ?? 'N/A') . "\n";
    echo "Email: " . ($responseData['user']['email'] ?? 'N/A') . "\n";
} else {
    echo "\n=== REGISTRATION FAILED ===\n";
    echo "Error: " . ($responseData['message'] ?? 'Unknown error') . "\n";
    if (isset($responseData['errors'])) {
        echo "Validation Errors:\n";
        print_r($responseData['errors']);
    }
}