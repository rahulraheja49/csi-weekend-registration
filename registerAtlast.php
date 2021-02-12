<?php
session_start();
require_once "connection.php";
require_once "util.php";
flashMessages();

if(isset($_POST['add'])){
    $_SESSION['teamName'] = $_POST['teamName'];
    $_SESSION['totMem'] = 0;
    for($i=1; $i<=4; $i++){
        if(isset($_POST['mem'.$i.'e'])){
            if(strlen($_POST['mem'.$i.'e'])>1){
                $_SESSION['mem'.$i.'eSet'] = "set";
                $_SESSION['mem'.$i.'e'] = $_POST['mem'.$i.'e'];
                $_SESSION['totMem'] = $_SESSION['totMem'] + 1;
            }
        }
    }
    if($_SESSION['totMem']<2){
        $_SESSION['error'] = "Minimum two members required";
        header("Location: registerAtlast.php");
        return;
    }
    
    $_SESSION['add'] = "Set";
    header("Location: registerAtlast.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atlast</title>
</head>
<body>
    <h2>Team Details</h2>
    <h3>All fields are required</h3>
    <form method="post">
        <p>
            <label for="teamName">Team Name</label>
            <input type="text" name="teamName" >
        </p>
        <p>
            <label for="mem1e">Member 1 MSTeams ID</label>
            <input type="text" name="mem1e" >
        </p>
        <p>
            <label for="mem2e">Member 2 MSTeams ID</label>
            <input type="text" name="mem2e" >
        </p>
        <p>
            <label for="mem3e">Member 3 MSTeams ID</label>
            <input type="text" name="mem3e" >
        </p>
        <p>
            <label for="mem4e">Member 4 MSTeams ID</label>
            <input type="text" name="mem4e" >
        </p>
        <p>
            <input type="submit" value="Apply" name="add">
            <a href="index.php">Cancel</a>
        </p>
    </form>
    <?php
    if(isset($_SESSION['add'])){
        for($i=1; $i<=4; $i++){
            if(isset($_SESSION['mem'.$i.'eSet'])){
                $stmt = $pdo->prepare('SELECT email FROM atlasT WHERE email = :mail');
                $stmt->execute(array(
                    ":mail" => htmlentities($_SESSION['mem'.$i.'e'])
                ));
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if($row === false){
                    continue;
                } else {
                    unset($_SESSION['add']);
                    $_SESSION['error'] = "One member can register only once";
                    header("Location: registerAtlast.php");
                    return;
                }
            }
        }
        if(isset($_SESSION['add'])){
            for($i=1; $i<=4; $i++){
                if(isset($_SESSION['mem'.$i.'eSet'])){
                    $t = time();
                    $stmt = $pdo->prepare('INSERT INTO atlasT (teamName, email)
                    VALUES (:teamName, :email)');
                    $stmt->execute(array(
                        ":teamName" => htmlentities($_SESSION['teamName']),
                        ":email" => htmlentities($_SESSION['mem'.$i.'e']),
                    ));
                } else {
                    continue;
                }
            }
            unset($_SESSION['add']);
            $_SESSION['success'] = "Registered for atlasT";
            header("Location: index.php");
            return;
        }
    }
    ?>
</body>
</html>