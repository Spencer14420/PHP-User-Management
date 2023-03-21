<?php

require_once __DIR__ . "/User.php";

//Adds methods to change data associated
//with the user in the database
class EditableUser extends User
{
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
            $this->username = $newUsername;
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
