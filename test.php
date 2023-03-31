<?php
require './aws/aws-autoloader.php';

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;

$credentials = new Credentials('009cb166bfa11e21c87d', 'EMnDN8HUUZYLQPeUCND2usD3Z6A0ns+Lb8aw3b9C');
// Instantiate the S3 client
$s3 = new S3Client([
    'version' => 'latest',
    'region' => 'pvn',
    'endpoint' => 'https://s3-north.viettelidc.com.vn',
    'credentials' => $credentials,
    'use_path_style_endpoint' => true,
]);

try {
    // Upload a file to Amazon S3
    $result = $s3->putObject([
        'Bucket' => 'video',
        'Key' => '642572b711c976.33482409.mp4',
        'Body' => fopen("./videos/1642572b711c976.33482409.mp4", 'r'),
        'ACL'    => 'public-read',
    ]);
} catch (Exception $e) {
    echo "error <hr>";
    echo $e->getMessage();
}

echo '<pre>', print_r($result), '</pre>';