<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
</head>

<body>
    <?php
    include_once __DIR__ . "/includes/userHeader.php";

    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        include_once __DIR__ . "/includes/processGet.php";
    } elseif ($_SERVER['REQUEST_METHOD'] === "POST") {
        include_once __DIR__ . "/includes/processPost.php";
    }


    ?>
</body>

</html>