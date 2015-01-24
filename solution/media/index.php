<?php

$images = array("http://cdn.highwire.com/8468049-03899789-334f-40ed-ab91-892cf82568a3.jpg"," http://cdn.highwire.com/8468049-588bc975-c755-42b2-9ea9-1a1450982149.jpg","http://cdn.highwire.com/8466328-890806a8-e1e2-4077-a71d-ce13f3df81e8.jpg"," http://cdn.highwire.com/8466328-ce05af53-804e-4eb8-ae34-c91750f27a63.jpg"," http://cdn.highwire.com/8466328-e93fe78c-4d2b-4d94-888d-0b25179f4072.jpg","http://cdn.highwire.com/8466145-7634920a-859a-45a4-9931-7845ca30c307.jpg"," http://cdn.highwire.com/8466145-9a634f03-5d2c-4a1d-8cec-e05796187409.jpg"," http://cdn.highwire.com/8466145-10e9e53e-7209-4fb9-afda-805ecf30d242.jpg","http://cdn.highwire.com/8466117-f5a41252-bd5c-4c71-80fc-dae50ecf19c0.jpg"," http://cdn.highwire.com/8466117-7ce710c1-f1dd-4bd2-8a11-f680c9ef61e7.jpg","http://cdn.highwire.com/8466094-904f08f9-2077-4346-839b-1afb6492670f.jpg"," http://cdn.highwire.com/8466094-b79bffaf-a7cf-4afa-a836-c25a9c87ea6e.jpg"," http://cdn.highwire.com/8466094-af0db92e-0e63-49b8-b596-cf1465ccf305.jpg"," http://cdn.highwire.com/8466094-295b5fe7-dcae-4679-ba3c-35aaa9dc1dba.jpg");
$i=1;
foreach($images as $name=>$image) {

 
    $imageData = file_get_contents($image);

    $name = explode("/", $image);
    
    $handle = fopen("/var/www/test/magento_1810_2/media/images/".$name[3],"x+");     

    fwrite($handle,$imageData);

    fclose($handle);
  echo $i;
  $i++;
}

?>