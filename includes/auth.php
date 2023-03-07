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

    public function setUserid($username, $mysqli)
    {
        $query = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $query->bind_param("s", $username);
        $query->execute();
        $result = $query->get_result();
        $this->userid = $result->fetch_assoc()['id'];
    }

    //Return array of permissions the user has
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

    public function setGroups($mysqli)
    {
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
}

function auth($currentUser, $mysqli, $perms)
{
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
    $currentUser->setUserid($username, $mysqli);

    //Get explicitly set groups
    $currentUser->setGroups($mysqli);
    $currentUser->setPerms($perms, $currentUser->groups);
}

auth($currentUser, $mysqli, $perms);
