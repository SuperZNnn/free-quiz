<?php

    require "vendor/autoload.php";

    use Endroid\QrCode\QrCode;
    use Endroid\QrCode\Writer\PngWriter;
    use Endroid\QrCode\Label\Label;
    use Endroid\QrCode\Logo\Logo;

    $text = "With your Lazer";

    $qr_code = QrCode::create($text);
    $logo = Logo::create('assets/images/logo256.png')->setResizeToWidth(50);
    $writer = new PngWriter;
    
    $result = $writer->write($qr_code,$logo);
    $base64 = base64_encode($result->getString());
    $imgData = 'data:image/png;base64,' . $base64;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code</title>
</head>
<body>
    <img src="<?php echo $imgData; ?>" alt="QR Code">
</body>
</html>