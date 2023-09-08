<?php
require_once __DIR__ . "/../../config/mysql.php";

class RequestedAccount
{
    public $username;
    public $sanitizedUsername;
    public $email;
    public $sanitizedEmail;
    public $exists;

    function __construct($usernameOrEmail, $enteredEmail = false)
    {
        if ($enteredEmail) {
            $this->email = $usernameOrEmail;
            $this->username = $this->getUsernameFromEmail();
        } else {
            $this->username = $usernameOrEmail;
            $this->email = $this->getEmailFromUsername();
        }
        $this->sanitizedUsername = htmlspecialchars($this->username);
        $this->sanitizedEmail = htmlspecialchars($this->email);
        $this->checkIfExists();
    }

    private function checkIfExists()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT * FROM req_accounts WHERE name = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();
        if (mysqli_num_rows($result) === 0) {
            $this->exists = false;
        } else {
            $this->exists = true;
        }
    }

    public function deleteReq()
    {
        global $currentUser;
        if ($currentUser->hasPerm("requestaccount-review")) {
            global $mysqli;
            $query = $mysqli->prepare("DELETE FROM req_accounts WHERE name = ?");
            $query->bind_param("s", $this->username);
            $query->execute();
            exit("<b>Success</b><br><br>Request from <b>$this->sanitizedUsername</b> has been deleted");
        } else {
            exit("Sorry, you cannot review account requests");
        }
    }

    private function getUsernameFromEmail()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT name FROM req_accounts WHERE email = ?");
        $query->bind_param("s", $this->email);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc()['name'];
    }

    private function getEmailFromUsername()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT email FROM req_accounts WHERE name = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc()['email'];
    }
}
