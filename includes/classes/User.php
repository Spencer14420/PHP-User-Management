<?php
require_once __DIR__ . "/../../config/mysql.php";
require_once __DIR__ . "/../../config/defaultPerms.php";

class User
{
    public $username;
    protected $userid;
    private $groups;
    private $userPerms;

    function __construct($username)
    {
        $this->username = $username;
        $this->setUserid();
        $this->setGroups();
        $this->setPerms();
    }

    private function setUserid()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
        $query->bind_param("s", $this->username);
        $query->execute();
        $result = $query->get_result();
        $this->userid = $result->fetch_assoc()['id'];
    }

    private function setGroups()
    {
        global $mysqli;
        $query = $mysqli->prepare("SELECT groupname FROM groups WHERE userid = ?");
        $query->bind_param("i", $this->userid);
        $query->execute();
        $result = $query->get_result();
        $groups = ["all"];
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
}
