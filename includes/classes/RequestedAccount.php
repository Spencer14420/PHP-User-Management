<?php
require_once __DIR__ . "/../../config/mysql.php";

class RequestedAccount
{
    public $username;
    public $exists;

    function __construct($username)
    {
        $this->username = $username;
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
            exit("<b>Success</b><br><br>Request from <b>$this->username</b> has been deleted");
        } else {
            exit("Sorry, you cannot review account requests");
        }
    }
}
