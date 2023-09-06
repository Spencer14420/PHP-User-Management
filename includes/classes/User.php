<?php
require_once __DIR__ . "/../../config/mysql.php";
require_once __DIR__ . "/../../config/defaultPerms.php";

class User
{
    public $username;
    public $email;
    public $exists;
    public $deleted;
    public $formattedUsername;
    protected $userid;
    private $groups;
    private $userPerms;

    function __construct($usernameOrEmail, $enteredEmail = false)
    {
        if ($enteredEmail) {
            $this->email = $usernameOrEmail;
            $this->username = $this->getUsernameFromEmail();
        } else {
            $this->username = htmlspecialchars($usernameOrEmail);
            $this->email = $this->getEmailFromUsername();
        }
        $this->setUseridAndExists();
        $this->checkIfDeleted();
        $this->setFormattedUsername();
        $this->setGroups();
        $this->setPerms();
    }

    //Displays the username with a strikethough if it is deleted
    protected function setFormattedUsername()
    {
        if ($this->deleted) {
            $this->formattedUsername = "<s>$this->username</s>";
        } else {
            $this->formattedUsername = $this->username;
        }
    }

    private function setUseridAndExists()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();
        if (mysqli_num_rows($result) === 0) {
            $this->userid = -1;
            $this->exists = false;
        } else {
            $this->userid = $result->fetch_assoc()['id'];
            $this->exists = true;
        }
    }

    private function checkIfDeleted()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT deleted FROM users WHERE username = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();

        if ($result->fetch_assoc()['deleted'] == 0) {
            $this->deleted = false;
        } else {
            $this->deleted = true;
        }
    }

    private function setGroups()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT groupname FROM groups WHERE userid = ?");
        $query->bind_param("i", $this->userid);
        $query->execute();
        $result = $query->get_result();
        $groups = ["all"];

        //Add user to implicit "user" group if they are logged in (i.e. username is not false)
        if ($this->username !== false) {
            $groups[] = "user";
        }

        while ($row = $result->fetch_assoc()) {
            $groups[] = $row['groupname'];
        }
        $this->groups = $groups;
    }

    //Set array of permissions the user has
    private function setPerms()
    {
        global $perms; //Defined in defaultPerms.php
        foreach ($this->groups as $group) {
            foreach ($perms[$group] as $perm) {
                $this->userPerms[] =  $perm;
            }
        }
    }

    public function hasPerm($perm)
    {
        return in_array($perm, $this->userPerms);
    }

    public function inGroup($group)
    {
        return in_array($group, $this->groups);
    }

    private function getUsernameFromEmail()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT username FROM users WHERE email = ?");
        $query->bind_param("s", $this->email);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc()['username'];
    }

    private function getEmailFromUsername()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT email FROM users WHERE username = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();
        return $result->fetch_assoc()['email'];
    }
}
