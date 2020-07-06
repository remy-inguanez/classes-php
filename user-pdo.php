
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
        $db = new PDO('mysql:host=localhost;dbname=classes', 'root', '');
        return($db);
    }

    public function register($login, $password, $email, $firstname, $lastname)
    {
        $db = $this->connectdb();
        $password = password_hash($password, PASSWORD_BCRYPT);
        $requet = $db->prepare("INSERT INTO utilisateurs (login, password, email, firstname, lastname) VALUES ('$login', '$password', '$email', '$firstname', '$lastname')");
        $requet->execute();
        $userinfos = array('login' => $login, 'password' => $password, 'email' => $email, 'firstname' => $firstname, 'lastname' => $lastname);
        return($userinfos);
    }

    public function connect($login, $password)
    {
        $db = $this->connectdb();
        $requet = $db->prepare("SELECT id FROM utilisateurs WHERE login = '$login'");
        $requet->execute();
        $checkuser = $requet->rowCount();
        if($checkuser == 1)
        {
            $requet = $db->prepare("SELECT id, login, email, firstname, lastname FROM utilisateurs WHERE login = '$login'");
            $requet->execute();
            $logininfos = $requet->fetchAll();
            $this->id = $logininfos[0]['id'];
            $this->login = $logininfos[0]['login'];
            $this->email = $logininfos[0]['email'];
            $this->firstname = $logininfos[0]['firstname'];
            $this->lastname = $logininfos[0]['lastname'];

            $result = array('login' => $this->login, 'email' => $this->email, 'firstname' => $this->firstname, 'lastname' => $this->lastname);
        }
        else
        {
            $result = "Problème lors de l'inscription";
        }
        
        return($logininfos);
    }

    public function disconnect()
    {
        session_destroy();
    }

    public function delete()
    {
        $db = $this->connectdb();
        $requet = $db->prepare("DELETE FROM utilisateurs WHERE utilisateurs.id = '$this->id'");
        session_destroy();
    }

    public function update($login, $password, $email, $firstname, $lastname)
    {
        $db = $this->connectdb();
        $password = password_hash($password, PASSWORD_BCRYPT);
        $requet = $db->prepare("UPDATE utilisateurs SET login = '$login', password = '$password', email = '$email', firstname = '$firstname', lastname = '$lastname' WHERE utilisateurs.id = $this->id");
        $requet->execute();
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
        $requet = $db->prepare("SELECT login, email, firstname, lastname FROM utilisateurs WHERE id = '$this->id'");
        $requet->execute();
        $infos = $requet->fetchAll();
        $this->login = $infos[0]['login'];
        $this->email = $infos[0]['email'];
        $this->firstname = $infos[0]['firstname'];
        $this->lastname = $infos[0]['lastname'];
    }
}

?>
