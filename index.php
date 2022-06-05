<?php
session_start();

if (!isset($_SESSION['UserData']['Username'])) {
    header("location:login.php");
    exit;
}
?>


<!DOCTYPE html>
<html>

<head>
    <style>
       <?php include 'css/style.css'; ?>
    </style>
    
    <title>File System Browser</title>
</head>
<body>
    <?php
    print('<h3 style="color:#FFF;">Congratulation! You have logged into password protected page.<a href="logout.php" style="color:yellow;">Click here</a> to Logout.</h3>');
    print('<br>');
    print('<h1 style="color:#82b74b; text-align:center;">File System Browser</h1>');

    //folder and files list
    $path = isset($_GET["path"]) ? './' . $_GET["path"] : './';
    $files_and_dirs = scandir($path);
    print('<table><th>Type</th><th>Name</th><th>Actions</th>');
    foreach ($files_and_dirs as $fnd) {
        if ($fnd != ".." and $fnd != ".") {
            print('<tr>');
            print('<td>' . (is_dir($path . $fnd)
                ? '<img src= "./images/folder.png" class="dir">'
                : '<img src= "./images/file.jpg" class="file">') 
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
    print("</table>");
    
    //create new folder
    $msgCreate = "Folder was created!";
    $dir = $path;
    if(isset($_REQUEST['path'])) {
        $dir = urldecode($_REQUEST['path']);
    }

    if(isset($_REQUEST['createfolder'])) {
        $foldername = trim($_REQUEST['createfolder']);    
        if(mkdir($dir . "/" .$foldername, 0777, true)){        
            header("location: ?path=" . $dir);
        }
    }
    ?>
    <form action="" method="POST" >
        <div class="createField">
            <input type="text" name="createfolder" class="createInput">
            <input type="submit" value="Create folder" class="createButton">
        </div>
    </form>
</body>
</html>