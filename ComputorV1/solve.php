<?php
class Solve
{
    //I know what you thinking... 
    private function check_xn($str, $obj)
    {
        $n = 0;
        foreach($obj->xn as $xn=>$co)
        {
            if ($co != 0)
            {
                $n = $xn;
                if ($co < -1)
                    $str = sprintf("%s - %s * X^%s", $str, $co * -1, $xn);
                else if ($co > 1)
                    $str = sprintf("%s + %s * X^%s", $str, $co, $xn);
                if ($co == 1)
                    $str = sprintf("%s + X^%s", $str, $xn);
                else if ($co == -1)
                    $str = sprintf("%s - X^%s", $str, $xn);
            }
        }
        if ($n != 0)
        {
            echo "Reduced form: ".$str."\n";
            echo "Polynomial degree: ".$n."\n";
            echo "The polynomial degree is stricly greater than 2, I can't solve.\n";
            exit(1);
        }
    }
    //manages program output
    private function output($obj)
    {
        $str = "";
        //linear part of the equation
        if ($obj->c > 0 || $obj->c < 0)
            $str = sprintf("%s", $obj->c);
        if ($obj->x > 1 && $str != "" )
            $str = sprintf("%s + %s * X", $str, $obj->x);
        else if ($obj->x > 1)
            $str = sprintf("%s * X", $obj->x);
        if ($obj->x == 1 && $str != "")
            $str = sprintf("%s + X", $str);
        else if ($obj->x == 1)
            $str = sprintf("X");
        if ($obj->x < -1 && $str != "")
            $str = sprintf("%s - %s * X", $str, $obj->x * -1);
        else if ($obj->x < -1)
            $str = sprintf("%s * X", $obj->x);
        if ($obj->x == -1 && $str != "")
            $str = sprintf("%s - X", $str);
        else if ($obj->x == -1)
            $str = sprintf("-X");
        
            //quadratic part of the equation
        if ($obj->x2 > 1 && $str != "")
            $str = sprintf("%s + %s * X^2", $str, $obj->x2);
        else if ($obj->x2 > 1)
            $str = sprintf("%s * X^2", $obj->x2);
        if ($obj->x2 == 1 && $str != "")
            $str = sprintf("%s + X^2", $str);
        else if ($obj->x2 == 1)
            $str = sprintf("X^2");
        if ($obj->x2 == -1 && $str != "")
            $str = sprintf("%s - X^2", $str);
        else if ($obj->x2 == -1)
            $str = sprintf("-X^2");
        if ($obj->x2 < -1 && $str != "")
            $str = sprintf("%s - %s * X^2", $str, $obj->x2 * -1);
        else if ($obj->x2 < -1)
            $str = sprintf("%s * X^2", $obj->x2);
        $this->check_xn($str, $obj);
        if ($obj->x2 == 0 && $obj->x != 0)
            echo "Reduced form: ".$str." = 0\nPolynomial degree: 1\n";
        else if ($obj->x2 != 0)
            echo "Reduced form: ".$str." = 0\nPolynomial degree: 2\n";
    }
    //determines the square of a number
    private function square($n)
    {
        return $n * $n;
    }
    //calculates the margin of error between desired result and estimate
    private function errP($a, $b)
    {
        $c = $a - $b;
        if ($c < 0)
            $c = $c * -1;
        if ($c < 0.0000000000001)
            return TRUE;
        else
            return FALSE;
    }
    //calculates the square root
    private function resolve_root($n, $c)
    {
        if ($n == 0)
            return (0);
        $c = ($c + ($n / $c)) / 2;
        while($this->errP($n/$c, $c) === FALSE)
            $c = ($c + ($n / $c)) / 2;
        return $c;
    }
    //yeah I know right, why have this here while the one above does the work...?
    private function sqroot($n)
    {
        return $this->resolve_root($n, 1);
    }
    //responsible for reducing the given input to simpler standardized equation
    private function reduce($obj)
    {
        //this is responsible for summing up all the x^2 coefficients on the left hand side of the equation
        foreach($obj->l_x2 as $val)
            $obj->x2 += $val;
        //this is responsible for summing up all the x^1 coefficients on the left hand side othe eqaution
        foreach($obj->l_x as $val)
            $obj->x += $val;
        //I suppose you can figure out what this does :-)
        foreach($obj->l_c as $val)
            $obj->c += $val;
        //clearly you must understand what this does, clue; takes care of the right hand side of the equation
        foreach($obj->r_x2 as $val)
            $obj->x2 -= $val;
        foreach($obj->r_x as $val)
            $obj->x -= $val;
        foreach($obj->r_c as $val)
            $obj->c -= $val;
        //considering the fact that the program shouldn't solve X^n, n > 2 figure out what this does :-(:)
        foreach($obj->l_xn as $key=>$n)
        {
            foreach($n as $val)
            {
                if (!isset($obj->xn[$key]))
                    $obj->xn += array($key=>$val);
                else
                    $obj->xn[$key] += $val;
            }
        }
        foreach($obj->r_xn as $key=>$n)
        {
            foreach($n as $val)
            {
                if (!isset($obj->xn[$key]))
                    $obj->xn += array($key=>$val);
                else
                    $obj->xn[$key] -= $val;
            }
        }
        $this->output($obj);
    }
    //as the name suggest it solves a quadratic equation, i.e polynomial degree 2
    private function quadratic($obj)
    {
        $a = $obj->x2;
        $b = $obj->x;
        $c = $obj->c;
        $des = $this->square($b) - 4 * $a * $c;
        $i = "";
        if ($des < 0)
        {
            $str = "Discriminant is negative,";
            $i = "i";
            $des *= -1;
            $x1 = sprintf("%s + %s", -1 * $b/(2 * $a), $this->sqroot($des)/(2 * $a));
            $x2 = sprintf("%s - %s", -1 * $b/(2 * $a), $this->sqroot($des)/(2 * $a));
        }
        else
        {
            $x1 = (-1 * $b + $this->sqroot($des)) / (2 * $a);
            $x2 = (-1 * $b - $this->sqroot($des)) / (2 * $a);
            $str = "Discriminant is strictly positive,";
        }
        $str = sprintf("%s the two solutions are:\n%s%s\n%s%s\n", $str, $x1, $i, $x2, $i);
        echo $str;

    }
    //as the name suggest it solves a linear equation, i.e polynomial degree 1
    private function linear($obj)
    {
        if ($obj->x == $obj->c && $obj->x == 0)
        {
            $str =  "Every real number of X is a true solution\n";
            $str = sprintf("Reduced form: %s = %s\n%s", $obj->c, $obj->x, $str);
            echo $str;
            return ;
        }
        else if ($obj->x == 0 && $obj->c != 0)
        {
            $str = "The equation is inconsistent i.e not solvable\n";
            $str = sprintf("Reduced form: %s = %s\n%s", $obj->c, $obj->x, $str);
            echo $str;
            return ;
        }
        $this->linearSteps($obj->x, $obj->c);
        $res = $obj->c / $obj->x * -1;
        $str = sprintf("The solution is: %s\n", $res);
        echo $str;
    }
    private function linearSteps($m, $c)
    {
        
    }
    //I think it is simple enough
    private function solution($obj)
    {
        if ($obj->x2 != 0)
            $this->quadratic($obj);
        else
            $this->linear($obj);
    }
    //...
    public function resolve($obj)
    {
        $this->reduce($obj);
        $this->solution($obj);
    }
}
?>