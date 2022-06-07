<?php
session_start();

if (!isset($_SESSION['UserData']['Username'])) {
    header("location:login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        <?php include 'css/style.css'; ?>
    </style>
    <title>File System Browser</title>
</head>

<body>
    <?php
    print('<h3 class="logoutText";">Congratulation! You have logged into password protected page.<a href="logout.php" style="color:yellow;">Click here</a> to Logout.</h3>');
    print('<br>');
    print('<h1 style="color:#fff; text-align:center;">File System Browser</h1>');
 
    //create new folder
    $dir = "./";
    if (isset($_REQUEST['path'])) {
        $dir = urldecode($_REQUEST['path']);
    }

    if (isset($_REQUEST['createfolder'])) {
        $foldername = trim($_REQUEST['createfolder']);
        if (mkdir($dir . "/" . $foldername, 0777, true)) {
            print('<p class="warning">Directory was created!</p>');
        } else if($foldername == false){
            print('<p class="warning">Failed to create directory! Please write directory name</p>');
        } else {
            print('<p class="warning">Such a directory already exists!</p>');
        }
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <!-- create folder form -->
        <div class="createField">
            <input type="text" name="createfolder" class="createInput" />
            <input type="submit" name="submit" value="Create folder" class="createButton" />
        </div>
    </form>

    <?php
    //upload files
    if(isset($_FILES['image'])){
        $errors= array();
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $file_name_arr = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($file_name_arr));
        $extensions = array("jpeg","jpg","png");
        if(in_array($file_ext,$extensions)=== false){

            $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
        }
        if($file_size > 2097152) {
            $errors[] ='File size must be smaller than 2 MB';
        }
        if(empty($errors)==true) {
            move_uploaded_file($file_tmp,"./" . $_GET["path"]. $file_name);
            echo "Success";
            header ("refresh:1");
        }else{
            print_r($errors);
        }
        print('<td>' .($_FILES['image']['name']) . '</td>');
    }
    
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="uploadField">
            <input type="file" name="image" class="uploadInput"/>
            <input type="submit" value="Upload files" class="createButton"/>
        </div>
    </form>

    <?php
    //folder and files list
    $path = isset($_GET["path"]) ? './' . $_GET["path"] : './';
    $files_and_dirs = scandir($path);
    print('<table><th>Type</th><th>Name</th><th>Actions</th>');
    foreach ($files_and_dirs as $fnd) {
        if ($fnd != ".." and $fnd != ".") {
            print('<tr>');
            print('<td>' . (is_dir($path . $fnd)
                ? '<img src= "./images/folder.png" class="dir" />'
                : '<img src= "./images/file.jpg" class="file" />')
                . '</td>');
            print('<td>' . (is_dir($path . $fnd)
                ? '<a href="' . (isset($_GET['path'])
                    ? $_SERVER['REQUEST_URI'] . $fnd . '/'
                    : $_SERVER['REQUEST_URI'] . '?path=' . $fnd . '/') . '">' . $fnd . '</a>'
                : $fnd)
                . '</td>');
            print('<td></td>');
            print('</tr>');
        }
    }
    print('</table>');
    ?>
    
</body>
</html>