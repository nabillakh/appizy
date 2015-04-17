<?php

require(dirname(__FILE__) . '/uploader.php');

$appId = uniqid();
$uploadDir = dirname(__FILE__) . '/tmp/' . $appId;

mkdir($uploadDir, 0777);

$uploader = new FileUpload('uploadfile');
$uploader->allowedExtensions = ['ods'];


// Handle the upload
$result = $uploader->handleUpload($uploadDir);

if (!$result) {
    exit(json_encode([
        'success' => false,
        'msg'     => $uploader->getErrorMsg(),
    ]));
}

echo json_encode([
    'success'  => true,
    'appId'  => $appId,
    'filename' => $uploader->getFileName()
]);
