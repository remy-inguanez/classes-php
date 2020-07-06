<?php
class User
{
    private $id = 0;
    public $login = "";
    public $email = "";
    public $firstname = "";
    public $lastname = "";

    private function connectdb()
    {
        $db = mysqli_connect('localhost', 'root', '', 'classes');
        return($db);
    }

    public function register($login, $password, $email, $firstname, $lastname)
    {
        $db = $this->connectdb();
        $login = mysqli_real_escape_string($db, $login);
        $password = mysqli_real_escape_string($db, $password);
        $email = mysqli_real_escape_string($db, $email);
        $firstname = mysqli_real_escape_string($db, $firstname);
        $lastname = mysqli_real_escape_string($db, $lastname);
        $password = password_hash($password, PASSWORD_BCRYPT);
        mysqli_query($db, "INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES ('$login', '$password', '$email', '$firstname', '$lastname')");
        $userinfos = array('login' => $login, 'password' => $password, 'email' => $email, 'firstname' => $firstname, 'lastname' => $lastname);
        return($userinfos);
    }

    public function connect($login, $password)
    {
        $db = $this->connectdb();
        $login = mysqli_real_escape_string($db, $login);
        $password = mysqli_real_escape_string($db, $password);
        $checkuser = mysqli_num_rows(mysqli_query($db, "SELECT id FROM utilisateurs WHERE login = '$login'"));
        if($checkuser == 1)
        {
            $logininfos = mysqli_fetch_array(mysqli_query($db, "SELECT id, login, email, firstname, lastname FROM utilisateurs WHERE login = '$login'"));
            $this->id = $logininfos['id'];
            $this->login = $logininfos['login'];
            $this->email = $logininfos['email'];
            $this->firstname = $logininfos['firstname'];
            $this->lastname = $logininfos['lastname'];

            $result = array('login' => $this->login, 'email' => $this->email, 'firstname' => $this->firstname, 'lastname' => $this->lastname);
        }
        else
        {
            $result = "Problème lors de l'inscription";
        }
        
        return($result);
    }

    public function disconnect()
    {
        session_destroy();
    }

    public function delete()
    {
        $db = $this->connectdb();
        mysqli_query($db, "DELETE FROM utilisateurs WHERE utilisateurs.id = '$this->id'");
        session_destroy();
    }

    public function update($login, $password, $email, $firstname, $lastname)
    {
        $db = $this->connectdb();
        $login = mysqli_real_escape_string($db, $login);
        $password = mysqli_real_escape_string($db, $password);
        $email = mysqli_real_escape_string($db, $email);
        $firstname = mysqli_real_escape_string($db, $firstname);
        $lastname = mysqli_real_escape_string($db, $lastname);
        $password = password_hash($password, PASSWORD_BCRYPT);
        mysqli_query($db, "UPDATE utilisateurs SET login = '$login', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname' WHERE utilisateurs.id = $this->id;");
    }

    public function isConnected()
    {
        if(isset($this->id))
        {
            $isConnected = true; 
        }
        else
        {
            $isConnected = false;
        }
        return($isConnected);
    }

    public function getAllInfos()
    {
        $USERINFOS = array('login' => $this->login, 'email' => $this->email, 'firstname' => $this->firstname, 'lastname' => $this->lastname);
        return($USERINFOS);
    }

    public function getLogin()
    {
        return($this->login);
    }
    public function getEmail()
    {
        return($this->email);
    }
    public function getFirstname()
    {
        return($this->firstname);
    }
    public function getLastname()
    {
        return($this->lastname);
    }
    
    public function refresh()
    {
        $db = $this->connectdb();
        $infos = mysqli_fetch_array(mysqli_query($db, "SELECT login, email, firstname, lastname FROM utilisateurs WHERE id = '$this->id'"));
        $this->login = $infos['login'];
        $this->email = $infos['email'];
        $this->firstname = $infos['firstname'];
        $this->lastname = $infos['lastname'];
    }
}
?>