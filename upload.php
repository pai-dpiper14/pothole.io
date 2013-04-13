<?PHP
ini_set('display_errors', 1);
$db = new Mysqli("localhost", "root", "potholearsenal", "pothole"); 
if ($db->connect_errno){
	die('Connect Error: ' . $db->connect_errno);
}
$place = $_POST['typeButton'];
$description = $_POST['comment_box'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];

$uploaddir = getcwd();
$random_file_name = rand(1,1000000) . '-' .  basename($_FILES['userfile']['name']);
$uploadfile = "$uploaddir/user_images/$random_file_name";
$filename = $_FILES['userfile']['name'];
if(move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
        $picture_url = "http://pothole.io/user_images/$random_file_name";
}


$db->query("INSERT INTO tbl_places SET place='$place', description='$description', lat='$latitude', lng='$longitude', picture_url='$picture_url'");
$place_id = $db->insert_id;
//echo $place_id;
header("Location: http://pothole.io"); exit;
/*

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

*/



?>
