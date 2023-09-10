<?php

require_once __DIR__ . "/../standardReq.php";
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

            $oldFormattedUsername = $this->formattedUsername;
            $this->username = $newUsername;
            $this->sanitizedUsername = htmlspecialchars($this->username);
            $this->setFormattedUsername();
            echo "<b>Success</b><br><br><b>$oldFormattedUsername</b> has been renamed to <b>$this->formattedUsername</b>";
            exit();
        } else {
            echo $GLOBALS['errorNoRenameusersPerm'];
            exit();
        }
    }

    public function editEmail($newEmail)
    {
        global $mysqli;
        global $currentUser;

        //Check if email is taken
        $query = $mysqli->prepare("SELECT * FROM users WHERE email = ?");
        $query->bind_param("s", $newEmail);
        $query->execute();
        $result = $query->get_result();

        if (mysqli_num_rows($result) > 0) {
            exit("<b>Error</b>: An account with the email address \"<b>{$newEmail}</b>\" already exists");
        }

        //Change email
        if ($currentUser->hasPerm("editemail")) {
            $query = $mysqli->prepare("UPDATE users SET email = ? WHERE email = ?");
            $query->bind_param("ss", $newEmail, $this->email);
            $query->execute();

            $this->email = $newEmail;
            $this->sanitizedEmail = htmlspecialchars($newEmail);
            echo "<b>Success</b><br><br><b>$this->sanitizedUsername's</b> email address has been changed to <b>$this->sanitizedEmail</b>";
            exit();
        } else {
            echo $GLOBALS['errorNoEditemailPerm'];
            exit();
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

            exit("<b>Success</b><br><br>Group membership of <b>$this->formattedUsername</b> has been changed");
        } else {
            echo $GLOBALS['errorNoGroupusersPerm'];
            exit();
        }
    }

    //Used by deleteUser() and undeleteUser()
    private function deleteUndeleteQuery($value)
    {
        global $mysqli;
        $query = $mysqli->prepare("UPDATE users SET deleted = ? WHERE id = $this->userid");
        $query->bind_param("i", $value);
        $query->execute();
    }

    public function deleteUser()
    {
        global $currentUser;
        if ($currentUser->hasPerm("deleteusers")) {
            $this->deleteUndeleteQuery(1); //Updates the "deleted" value in the database to 1 (i.e. true)
            $this->deleted = true;
            $this->setFormattedUsername(); //Updates formattedUsername (i.e. adds strikethrough)
            exit("<b>Success</b><br><br><b>$this->formattedUsername</b> has been deleted");
        } else {
            echo $GLOBALS['errorNoDeleteusersPerm'];
            exit();
        }
    }

    public function undeleteUser()
    {
        global $currentUser;
        if ($currentUser->hasPerm("undeleteusers")) {
            $this->deleteUndeleteQuery(0); //Updates the "deleted" value in the database to 0 (i.e. false)
            $this->deleted = false;
            $this->setFormattedUsername(); //Updates formattedUsername (i.e. removes strikethrough)
            exit("<b>Success</b><br><br><b>$this->formattedUsername</b> has been restored");
        } else {
            $GLOBALS['errorNoUndeleteusersPerm'];
            exit();
        }
    }
}
