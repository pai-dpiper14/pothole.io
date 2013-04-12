<?PHP
ini_set('display_errors', 1);
include '../aws-sdk-for-php/sdk.class.php';

$uploaddir = getcwd();
$uploadfile = $uploaddir. basename($_FILES['userfile']['name']);
$filename = $_FILES['userfile']['name'];

if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        echo "Uploaded file";
// <img src='http://ec2-54-234-98-217.compute-1.amazonaws.com/$filename' />";

//      $s3 = new AmazonS3();

//      $response = $s3->create_object('ph-content-pothole', $filename, array('fileUpload' => $uploadfile));
//print_r($response);
//      if($response->isOK()) { echo "file uploaded"; }

}

?>
