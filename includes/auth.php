<?php
require_once __DIR__ . "/../config/mysql.php";
require_once __DIR__ . "/../config/defaultPerms.php";

$currentUser = new User();
$currentUser->setUsername(false);
$currentUser->setPerms($perms, ["all"]);


class User
{
    public $userid;
    public $username;
    public $userPerms;
    public $groups;

    public function setUserid($username)
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        $this->userid = $result->fetch_assoc()['id'];
    }

    //Set array of permissions the user has
    public function setPerms($permslist, $groups)
    {
        foreach ($groups as $group) {
            foreach ($permslist[$group] as $perm) {
                $this->userPerms[] =  $perm;
            }
        }
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setGroups()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT groupname FROM groups WHERE userid = ?");
        $query->bind_param("i", $this->userid);
        $query->execute();
        $result = $query->get_result();
        $groups = [];
        while ($row = $result->fetch_assoc()) {
            $groups[] = $row['groupname'];
        }
        $this->groups = $groups;
    }

    public function hasPerm($perm)
    {
        return in_array($perm, $this->userPerms);
    }

    public function inGroup($group)
    {
        return in_array($group, $this->groups);
    }

    public function renameUser($newUsername)
    {
        global $mysqli;
        global $currentUser;

        //Check if username is taken
        $query = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $query->bind_param("s", $newUsername);
        $query->execute();
        $result = $query->get_result();

        if (mysqli_num_rows($result) > 0) {
            exit("<b>Error</b>: An account with the username \"<b>{$newUsername}</b>\" already exists");
        }

        //Rename user
        if ($currentUser->hasPerm("renameusers")) {
            $query = $mysqli->prepare("UPDATE users SET username = ? WHERE username = ?");
            $query->bind_param("ss", $newUsername, $this->username);
            $query->execute();
            echo "<b>Success</b><br><br><b>$this->username</b> has been renamed to <b>$newUsername</b>";
            $this->setUsername($newUsername);
            exit();
        } else {
            exit("Sorry, you cannot rename users");
        }
    }

    public function changeGroups($newGroups)
    {
        global $mysqli;
        global $currentUser;

        if ($currentUser->hasPerm("groupusers")) {
            //Remove all rows associated with the user from the groups table
            $query = $mysqli->prepare("DELETE FROM groups WHERE userid = ?");
            $query->bind_param("i", $this->userid);
            $query->execute();

            //Add rows for each added group
            foreach ($newGroups as $group) {
                $query = $mysqli->prepare("INSERT INTO groups (groupname, userid) values (?,?)");
                $query->bind_param("si", $group, $this->userid);
                $query->execute();
            }

            exit("<b>Success</b><br><br>Group membership of <b>$this->username</b> has been changed");
        } else {
            exit("Sorry, you cannot add or remove users from groups");
        }
    }
}

function auth($currentUser, $perms)
{
    global $mysqli;

    //Check if token cookie is set
    if (!isset($_COOKIE['sp-token'])) {
        return;
    }

    //Check if token matches anything in the database
    $hashtoken = hash("sha256", $_COOKIE['sp-token']);
    $query = $mysqli->prepare("SELECT username FROM users WHERE token = ?");
    $query->bind_param("s", $hashtoken);
    $query->execute();
    $result = $query->get_result();
    if (mysqli_num_rows($result) === 0) {
        return;
    }

    $username = $result->fetch_assoc()['username'];
    $currentUser->setUsername($username);
    $currentUser->setUserid($username);

    //Get explicitly set groups
    $currentUser->setGroups();
    $currentUser->setPerms($perms, $currentUser->groups);
}

auth($currentUser, $perms);
