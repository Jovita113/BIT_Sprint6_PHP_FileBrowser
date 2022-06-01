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
    print('<h2 style="color:#FFF; text-align:center;">File System Browser</h2>');

    $path = isset($_GET["path"]) ? './' . $_GET["path"] : './';
    $files_and_dirs = scandir($path);
    print('<table><th>Type</th><th>Name</th><th>Actions</th>');
    foreach ($files_and_dirs as $fnd) {
        if ($fnd != ".." and $fnd != ".") {
            print('<tr>');
            print('<td>' . (is_dir($path . $fnd) ? "Directory" : "File") . '</td>');
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
    ?>
</body>

</html>

