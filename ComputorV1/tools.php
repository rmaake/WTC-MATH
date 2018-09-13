<?php
class Tools
{
    //the function names explain themselves, please take your time to go through the implementation
    public function showError($cde)
    {
        if($cde == 0)
            echo "Usage: php main.php <polynomial equation of degree 2 or less>\n";
        if ($cde == 1)
            echo "Unidentified character / Invalid coefficient value detected!\nProgram exiting...\n";
        if ($cde == 2)
            echo "I can't solve for degree above 2 or less than -1\n";
        exit(1);
    }
    public function getSize($var)
    {
        $i = 0;
        foreach($var as $val)
            $i++;
        return ($i);
    }
    public function getCo($val)
    {
        $tmp = explode("*", $val);
        $co = trim($tmp[0], " ");
        if (is_numeric($co) == true)
            return ($co);
        else if (strcasecmp(substr($co, 0, 1), "X") == 0 && $this->getSize($tmp) < 2)
            return (1);
        else
            $this->showError(1);
    }
    public function getExp($val)
    {
        $tmp = explode("*", $val);
        if ($this->getSize($tmp) < 2)
            $tmp = explode("^", $tmp[0]);
        else
            $tmp = explode("^", $tmp[1]);
        if ($this->getSize($tmp) < 2)
        {
            if (strcasecmp("X", trim($tmp[0], " ")) == 0)
                return (1);
            else
                return (0);
        }
        else
            return (trim($tmp[1], " "));
    }
    public function getVar($val)
    {
        $tmp = explode("*", $val);
        if ($this->getSize($tmp) < 2)
            $tmp = explode("^", $tmp[0]);
        else
            $tmp = explode("^", $tmp[1]);
        if ($this->getSize($tmp) < 2)
        {
            $var = trim($tmp[0], " ");
            if (is_numeric($var) == true)
                return ("X");
            return (trim($tmp[0], " "));
        }
        else
            return (trim($tmp[0], " "));
    }
}
?>