<?php
require_once './header.php';
class getRight
{
    //so the whole purpose of this class is to get all variables on the right hand side of the equation
    private $tool;
    function __construct()
    {
        $this->tool = new Tools();
    }
   //very similar to that of getLeft.php take a look
    function addNegative($n_var, $obj)
    {
        $i = 0;
        foreach($n_var as $val)
        {
            if (!$val)
            {
                $i++;
                continue;
            }
            $co = $this->tool->getCo($val);
            if ($i != 0)
                $co = $co * -1;
            $exp = $this->tool->getExp($val);
            $var = $this->tool->getVar($val);
            if (strcasecmp($var,"X") != 0)
                $this->tool->showError(1);
            if($exp == 2)
                $obj->r_x2 = array_merge($obj->r_x2,array($co));
            else if ($exp == 1)
                $obj->r_x = array_merge($obj->r_x, array($co));
            else if ($exp == 0)
                $obj->r_c = array_merge($obj->r_c, array($co));
            else
            {
                if (!isset($obj->r_xn[$exp]))
                    $obj->r_xn += array($exp=>array($co));
                else
                    $obj->r_xn[$exp] = array_merge($obj->r_xn[$exp], array($co));
            }
            $i++;
        }
    }
    //...
    function addPositive($p_var, $obj)
    {
        foreach($p_var as $val)
        {
            $co = $this->tool->getCo($val);
            $exp = $this->tool->getExp($val);
            $var = $this->tool->getVar($val);
            if (strcasecmp($var, "X") != 0)
                $this->tool->showError(1);
            if($exp == 2)
                $obj->r_x2 = array_merge($obj->r_x2,array($co));
            else if ($exp == 1)
                $obj->r_x = array_merge($obj->r_x, array($co));
            else if ($exp == 0)
                $obj->r_c = array_merge($obj->r_c, array($co));
            else
            {
                if (!isset($obj->r_xn[$exp]))
                    $obj->r_xn += array($exp=>array($co));
                else
                    $obj->r_xn[$exp] = array_merge($obj->r_xn[$exp], array($co));
            }
        }
    }
    //as is
    function get_right($str, $obj)
    {
        $str = trim($str, " ");
        $p_var = explode("+", $str);
        $n_var = [];
        $i = 0;
        foreach($p_var as $val)
        {
            if (strchr($val, "-"))
            {
                $n_var = array_merge($n_var, explode("-", $val));
                unset($p_var[$i]);
            }
            $i++;
        }
        $this->addPositive($p_var, $obj);
        $this->addNegative($n_var, $obj);
    }
}
?>