<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Multiple File Upload</title>
    <style>
        .green {
            color: green;
        }

        .red {
            color: red;
        }
    </style>
</head>
<body>
<div>
    <div>
        <h3>
            Upload Index, APK, JSON Output Files
        </h3>
        <form id="dpForm" role="Form" action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post"
              enctype="multipart/form-data">
            Directory <input type="input" id="directory" name="directory" value="/" placeholder="uploads/"/>
            <br><br>
            Index .HTML File
            <input type="file" id="indexfile" name="indexfile" class="dropify"/>
            <br>
            <br> APK File
            <input type="file" id="apkfile" name="apkfile" class="dropify"/> <br>
            <br>
            Output .JSON File
            <input type="file" id="jsonfile" name="jsonfile" class="dropify"/> <br>
            <br>

            <div class="form-group clearfix">
                <input type="submit" name="submit" value="Submit">
            </div>
        </form>
    </div>
</div>

<?php


if (isset($_POST['submit']) && $_POST['submit'] == 'Submit') {
    $target_dir = "";
    $apk_target_dir = '';
    if (isset($_POST['directory']) && $_POST['directory'] != '' && $_POST['directory'] != '/') {
        $apk_target_dir = $_POST['directory'];
        $apk_target_dir = ltrim($apk_target_dir, '/');
        if (substr($apk_target_dir, -1) != '/') {
            $apk_target_dir = $apk_target_dir . '/';
        }
        if (is_dir($apk_target_dir) === false) {
            mkdir($apk_target_dir);
        }
    }


    /*Index HTML File*/
    $html_target_file = $target_dir . basename($_FILES["indexfile"]["name"]);
    $imageFileType = pathinfo($html_target_file, PATHINFO_EXTENSION);
    if (basename($_FILES["indexfile"]["name"]) != "index.html") {
        echo "<p class='red'>Sorry, only HTML files are allowed, as index.html file. </p>";
    } else {
        if (move_uploaded_file($_FILES["indexfile"]["tmp_name"], $html_target_file)) {
            echo '<p class="green">.HTML file uploaded successfully</p> ';
            $lines = file($html_target_file);
            $myFileLink2 = fopen($html_target_file, 'w+') or die("Can't open file.");
            foreach ($lines as $line_num => $line) {
                $newContents = str_replace('script', '   ', $line);
                fwrite($myFileLink2, $newContents);
            }
            fclose($myFileLink2);
        } else {
            echo "<p class='red'>Sorry, there was an error while uploading your index HTML file</p> ";
        }
    }

    /*APK   File*/
    $apk_target_file = $apk_target_dir . basename($_FILES["apkfile"]["name"]);
    $imageFileType = pathinfo($apk_target_file, PATHINFO_EXTENSION);
    if ($imageFileType != "apk") {
        echo "<p class='red'>Sorry, only APK files are allowed for APK file. </p>";
    } else {
        if (move_uploaded_file($_FILES["apkfile"]["tmp_name"], $apk_target_file)) {
            echo '<p class="green">.APK file uploaded successfully </p>';
        } else {
            echo "<p class='red'>Sorry, there was an error while uploading your APK file </p>";
        }
    }

    /*JSON   File*/
    $json_target_file = $apk_target_dir . basename($_FILES["jsonfile"]["name"]);
    $imageFileType = pathinfo($json_target_file, PATHINFO_EXTENSION);
    if (basename($_FILES["jsonfile"]["name"]) != "output.json") {
        echo "<p class='red'>Sorry, only JSON files are allowed, output.json file.</p> ";
    } else {
        if (move_uploaded_file($_FILES["jsonfile"]["tmp_name"], $json_target_file)) {
            echo '<p class="green">.JSON file uploaded successfully</p> ';
        } else {
            echo "<p class='red'>Sorry, there was an error while uploading your Output JSON file</p> ";
        }
    }
}
?>
</body>
</html>