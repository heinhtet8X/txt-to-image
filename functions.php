
function text_to_png($text, $is_smbc = false) {
    // Check if the text is empty
    if (empty($text)) {
        return null;
    }
  
    // Create a blank image with a specified width and height
    $w = 320;
    $h = 50;
    if($is_smbc) {
      $w = 270;
      $h = 30;
    }
    $image = imagecreatetruecolor($w, $h);
    //$image = imagecreatefrompng (__DIR__ . '/../img/bg.png');
    // Set a background color (optional)
    $bgColor = imagecolorallocate($image, 255, 255, 255);
    imagefill($image, 0, 0, $bgColor);

    // Set the text color
    $textColor = imagecolorallocate($image, 0, 0, 0);

    // Set the font path
    $font = __DIR__ . '/../webfonts/NotoSansJP-Regular.ttf';    

    // Check if the font file exists
    if (!file_exists($font)) {
        return null;
    }
    $strlen = strlen($text); // before encode
  // apply encodign first
    $text = mb_convert_encoding($text, "HTML-ENTITIES", "UTF-8");
    $text = preg_replace('~^(&([a-zA-Z0-9]);)~',htmlentities('${1}'),$text);

    // Write the text on the image
    $x = 0;
    if($is_smbc) {
       $x = (imagesx($image)/2) - (8*($strlen/2));      
    }
    imagettftext($image, 18, 0, $x, 20, $textColor, $font, $text);

    // Output the image to a buffer
    ob_start();
    imagepng($image);
    $imageData = ob_get_clean();

    // Free up memory
    imagedestroy($image);

    // Encode the image data as base64
    $base64Image = base64_encode($imageData);

    // Construct the base64 URL
    $base64Url = 'data:image/png;base64,' . $base64Image;

    // Output the base64 URL
    return $base64Url;
}
