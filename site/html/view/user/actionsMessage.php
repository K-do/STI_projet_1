<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../../../databases/config.php';
global $connectionDb;
session_start();
if (!(isset($_SESSION['email']))) {
    header("Location:/view/loginView.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['deleteItem'])) {
        $sql = "DELETE FROM message WHERE id = :id AND receiver = :receiver";
        // Query the DB
        if ($stmt = $connectionDb->prepare($sql)) {
            $stmt->bindParam(':receiver', $email);
            $stmt->bindParam(':id', $id);
            $id = $_POST['id'];
            $email = $_SESSION['email'];
            if (!$stmt->execute()) {
                echo "ERROR";
            } else {
                header("Location: /view/user/userView.php");
            }
        }
    } else if (isset($_POST['displayItem'])) {
        $sql = "SELECT * FROM message WHERE receiver = :receiver AND id = :id ";
        // Query the DB
        if ($stmt = $connectionDb->prepare($sql)) {
            $stmt->bindParam(':receiver', $email);
            $stmt->bindParam(':id', $id);
            $id = $_POST['id'];
            $email = $_SESSION['email'];
            if (!$stmt->execute()) {
                echo "ERROR";
            } else {
                if ($data = $stmt->fetch()) {
                    echo "<div class=\"message\">";
                    echo "<h3> " . $data['sender'] . "</h3>";
                    echo "<h4> " . $data['subject'] . "</h4>";
                    echo "date_received: " . $data['date_received'] . "<br/>";
                    echo "body: " . $data['body'] . "<br/>";
                    echo "<a href='userView.php'>RETURN</a>";
                    echo "</div>";
                }
            }
        }
    } else if (isset($_POST['writeMessage'])) {
        $sql = "INSERT INTO message (subject, body, sender, receiver) VALUES (:subject, :body, :sender, :receiver)";
        // Query the DB
        if( !isset($_POST['subject']) || !isset($_POST['body']) || !isset($_POST['target'])){
            echo "MISSING NEEDED FIELD";
            print_r($_POST);
            echo "<a href='userView.php'>Return</a>";
        }

        if ($stmt = $connectionDb->prepare($sql)) {
            $stmt->bindParam(':receiver', $target);
            $stmt->bindParam(':sender', $email);
            $stmt->bindParam(':subject', $subject);
            $stmt->bindParam(':body', $body);
            $target = $_POST['target'];
            $body = $_POST['body'];
            $subject = $_POST['subject'];
            $email = $_SESSION['email'];
            if (!$stmt->execute()) {
                echo "ERROR";
            } else {
                header("Location: /view/user/userView.php");
            }
        }
    }
}