<?php
$name = '';
$email = '';
$password = '';
$errors = [];
$ch = "";
$genMale = "";
$genFe = "";
if (isset($_POST['submit'])) {

    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if ($name == '') {
        $errors[] = 'You must Enter a Name.';
    } else if (strlen($name) <= 4) { //>
        $errors[] = 'Name length must be greater than 4.';
    }

    if ($email == '') {
        $errors[] = 'You must Enter an Email.';
    } else if (!preg_match("/^[a-zA-Z0-9._%+-]+@gmail\\.[a-zA-Z]{3}/", $email)) { //>
        $errors[] = 'Enter a vaild email.';
    }

    if ($password == '') {
        $errors[] = 'You must Enter a Password.';
    } else if (strlen($password) < 8) { //>
        $errors[] = 'Password length must be greater than or equal 8.';
    }

    if (isset($_POST['check'])) {
        $ch = "checked";
    }

    if (isset($_POST['gender'])) {
        if ($_POST['gender'] == "male")  $genMale = "checked";
        else if ($_POST['gender'] == "female") $genFe = "checked";
    }

    if (!$_FILES['image']['name'] == "") {
        if ($_FILES['image']['size'] > 1024 * 1024) {
            $errors[] = 'Image size must be less than 1MB.';
        }
    } else {
        $errors[] = 'You must upload an Image.';
    }

    if (count($errors) == 0) {
        if (!file_exists('images')) {
            mkdir('images');
        }
        if (!move_uploaded_file($_FILES['image']['tmp_name'], getcwd() . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . pathinfo($_FILES['image']['name'], PATHINFO_FILENAME) . "_" . $name . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION))) {
            echo 'Error while uploading the Image.';
        } else {
            echo 'Image uploaded.';
        }
        $nameDB = htmlspecialchars($_POST['name']);
        $passDB = md5(htmlspecialchars($_POST['password']));
        $emailDB = htmlspecialchars($_POST['email']);
        $genderDB = $_POST['gender'] == "male" ? 1 : 2; //1 = Male , 2 = Female
        $imageNameDB = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME) . "_" . $name . "." . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if ($ch != "checked") {
            $name = '';
            $email = '';
            $password = '';
            $genMale = "";
            $genFe = "";
        }
        $server = "localhost";
        $username = "root";
        $password = "";
        $dbName = "homework";

        $con = mysqli_connect($server, $username, $password, $dbName);

        $stmt = $con->prepare("INSERT INTO users (name, password, email, gender, image_name)
        VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssis", $nameDB, $passDB, $emailDB, $genderDB, $imageNameDB);
        $stmt->execute();
        // $q = "insert into users set name='$nameDB',password='$passDB',email='$emailDB',gender='$genderDB',image_name='$imageNameDB';";
        // mysqli_query($con, $q);
        if (mysqli_error($con))
            echo "<br>Query Error!";
        else
            echo "<br>User added " . mysqli_insert_id($con);
    }
    // echo "<pre>";
    
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HW</title>
   
</head>
<div class="con">
    <h1>HW#7</h1>
    <?php
    if (count($errors) > 0) {
        echo '<ul>';
        foreach ($errors as $error) {
            echo "<li>$error</li>";
        }
        echo '</ul>';
    }
    ?>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="fiald-con">
            <label for="name">Name : </label>
            <input id="name" name="name" type="text" value="<?php echo $name
                                                            ?>">
        </div>
        <div class="fiald-con">
            <label for="email">Email : </label>
            <input id="email" name="email" type="text" value="<?php echo $email
                                                                ?>">
        </div>
        <div class="fiald-con">
            <label for="password">Password : </label>
            <input id="password" name="password" type="password" value="<?php echo $password
                                                                        ?>">
        </div>
        <div class="radio-con">
            <div class="">
                <input id="genMale" name="gender" value="male" type="radio" <?php echo $genMale ?>>
                <label for="genMale">Male</label>
            </div>
            <div class="">
                <input id="genFemale" name="gender" value="female" type="radio" <?php echo $genFe ?>>
                <label for="genFemale">FeMale</label>
            </div>
        </div>
        <div style="text-align: left; margin: 20px;">
            <label for="file">Upload File</label>
            <input id="file" name="image" type="file">
        </div>
        <div style="text-align: left; margin: 20px;">
            <input name='check' type="checkbox" id="check" <?php echo $ch ?>>
            <label for="check">Remember Me</label>
        </div>
        <button name="submit" type="submit">Submit</button>
    </form>
</div>

<body>

</body>

</html>

