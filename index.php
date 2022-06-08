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

    // creating a new folder
    $dir = "./";
    if (isset($_REQUEST['path'])) {
        $dir = urldecode($_REQUEST['path']);
    }
    if (isset($_REQUEST['createfolder'])) {
        $foldername = trim($_REQUEST['createfolder']);
        if ($foldername == false) {
            print('<p class="warning">Failed to create directory! Please write directory name</p>');
            header("refresh:3");
        } else if (mkdir($dir . "/" . $foldername)) {
            print('<p class="warning">Directory was created!</p>');
            header("refresh:3");
        } else{
            print('<p class="warning">Such a directory already exists!</p>');
            header("refresh:3");
        } 
    }
    print(' 
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="createField">
                    <input type="text" name="createfolder" class="createInput" />
                    <input type="submit" name="submit" value="Create folder" class="createButton" />
                </div>
            </form>
         ');

    // upload file logic
    if (isset($_FILES['image'])) {
        $errors = "";
        $file_name = $_FILES['image']['name'];
        $file_size = $_FILES['image']['size'];
        $file_tmp = $_FILES['image']['tmp_name'];
        $file_type = $_FILES['image']['type'];
        $exploded = explode('.', $_FILES['image']['name']);
        $file_ext = strtolower(end($exploded));
        $extensions = ["jpeg", "jpg", "png"];
        if (in_array($file_ext, $extensions) === false) {
            $errors = '<p class="warningUpl">File format is not allowed, please choose a JPEG or PNG file.</p>';
            header("refresh:3");
        }
        if ($file_size > 2097152) {
            $errors = '<p class="warningUpl">File size must be smaller than 2 MB</p>';
            header("refresh:3");
        }
        if (empty($errors) == true) {
            move_uploaded_file($file_tmp, "./" . $_GET['path']  . $file_name);
            echo '<p class="warningUpl">Success!File uploaded</p>';
            header("refresh:3");
        } else{
            print_r($errors);
        }
    }
    print('
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="uploadField">
                    <input type="file" name="image" class="uploadInput" />
                    <input type="submit" value="Upload files" class="createButton" />
                </div>
            </form>
        ');

    // file download logic
    if (isset($_POST['download'])) {
        $file = './' . $_POST['download'];
        $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, 0, 'utf-8'));
        ob_clean();
        ob_start();
        header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
        header('Content-Length: ' . filesize($fileToDownloadEscaped));
        ob_end_flush();
        readfile($fileToDownloadEscaped);
        exit;
    }

    // file delete logic
    if (isset($_POST['delete'])) {
        $file = './'  . $_POST['delete'];
        if (file_exists($file)) {
            unlink($file);
            print('<p class="warningDlt">File is deleted</p>');
        } else {
            echo '<p class="warningDlt">File is not deleted!</p>';
        }
    }

    // folder and files list
    $path = isset($_GET["path"])
        ? './' . $_GET["path"]
        : './';
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
            print('<td>' . (is_dir($path . $fnd)
                ? '<form action="?path=' . $path . $fnd . '" method="post">
                        <button class="downloadBtn" type="submit" name="download" value =' . $path . $fnd . '>DOWNLOAD</button>
                   </form>'
                : ($fnd === 'index.php'|| $fnd === 'login.php' || $fnd === 'logout.php' ||$fnd === 'Readme.md'
                    ? '<form action="?path=' . $path . $fnd . '" method="post">
                        <button class="downloadBtn" type="submit" name="download" value =' . $path . $fnd . '>DOWNLOAD</button>
                       </form>'
                    : '<form action="?path=' . $path . $fnd . '" method="post">
                          <button class="downloadBtn" type="submit" name="download" value =' . $path . $fnd . '>DOWNLOAD</button>
                       </form>' .
                       '<form action="" method="post">
                           <button class="deleteBtn" type="submit" name="delete" value =' . $path . $fnd . '>DELETE</button>
                        </form>' )
                )  
                . '</td>');
            print('</tr>');
        }
    }
    print('</table>');
    ?>
</body>

</html>