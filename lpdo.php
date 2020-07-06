
<?php

class lpdo
{
    public $db = null;
    private $lastquery = "";
    private $lastresult = "";

    public function constructeur($host, $username, $password, $db)
    {
        $db = mysqli_connect($host, $username, $password, $db);
        $this->db = $db;
    }
    public function connect($host, $username, $password, $db)
    {
        if($this->db)
        {
            mysqli_close($this->db);
            $db = mysqli_connect(''.$host.'', ''.$username.'', ''.$password.'', ''.$db.'');
            $this->db = $db;
        }
    }

    public function destructeur()
    {
        mysqli_close($this->db);
        $this->db = null;
    }

    public function close()
    {
        if($this->db)
        {
            mysqli_close($this->db);
        }
    }
    public function execute($query)
    {
        $result = mysqli_query($this->db, $query);
        $this->lastquery = $query;
        $this->lastresult = $result;
        return($result);
    }

    public function getLastQuery()
    {
        if(!empty($this->lastquery))
        {
            return($this->lastquery);
        }
        else
        {
            return(false);
        }
    }
    
    public function getLastResult()
    {
        if(!empty($this->lastresult))
        {
            return($this->lastresult);
        }
        else
        {
            return(false);
        }
    }

    public function getTables()
    {
        $result = mysqli_query($this->db, "SHOW TABLES FROM classes");
        return($result);
    }

    public function getFields($table)
    {
        $result = mysqli_fetch_all(mysqli_query($this->db, "SHOW FIELDS FROM $table"));
        return($result);
    }
}

?>
