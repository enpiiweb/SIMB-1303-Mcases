<?php

function do_select($list, $selec, $name, $key=false,$val=false, $onchange=false, $size=1)
{
    $sel = "<select name='$name'";
    
    $sel .= ($onchange)? " onChange='$onchange'" : "";
    
    $sel .= ($size>1)? " size='$size' multiple":"";
    
    $sel .= ">";
    if(is_array($list))
        foreach($list AS $v => $n)
        {
        	$k = false;
        	if($key)
        	{
        		if(is_array($n))
        		{
        			if(isset($n[$key]))
        				$k = $n[$key];
        		} else if(is_object($n))
        		{
        			if(isset($n->$key))
        				$k = $n->$key;
        		}
        	}
        	if($k===false)
        		$k = $v;
        	$x = false;
        	if($val)
        	{
        		if(is_array($n))
        		{
        			if(isset($n[$val]))
        				$x = $n[$val];
        		} else if(is_object($n))
        		{
        			if(isset($n->$val))
        				$x = $n->$val;
        		}
        	}
        	if($x===false)
        		$x = $n;
        	
        	
            $sel .= "<option value='$k'";
            if(is_array($selec))
            {
					if(in_array($k,$selec))
						$sel .= " selected";
            } else {
				if($selec==$k)
					$sel .= " selected";
				
            }
            $sel .= ">$x</option>";
        
        }
    else
        $sel .= "<option disabled value=unavailable>None Available</option>";
    $sel .= "</select>";
    return $sel;

}

function do_radio($list, $selec, $name, $key=false,$val=false)
{  
    if(is_array($list))
        foreach($list AS $v => $n)
        {
        	$k = false;
        	if($key)
        	{
        		if(is_array($n))
        		{
        			if(isset($n[$key]))
        				$k = $n[$key];
        		} else if(is_object($n))
        		{
        			if(isset($n->$key))
        				$k = $n->$key;
        		}
        	}
        	if($k===false)
        		$k = $v;
        	$x = false;
        	if($val)
        	{
        		if(is_array($n))
        		{
        			if(isset($n[$val]))
        				$x = $n[$val];
        		} else if(is_object($n))
        		{
        			if(isset($n->$val))
        				$x = $n->$val;
        		}
        	}
        	if($x===false)
        		$x = $n;
            $rad .= "<input type='radio' name='$name' value='$k'";
            if($selec==$v)
                $rad .= " checked";
            $rad .= ">$x<br>";
        
        }
    else
        $rad .= "No options available<br>";
    return $rad;

}

function do_checkbox($list, $selec, $name, $key=false,$val=false)
{  
	if(!is_array($selec))
		$selec = array($selec);
    if(is_array($list))
        foreach($list AS $v => $n)
        {
        	$k = false;
        	if($key)
        	{
        		if(is_array($n))
        		{
        			if(isset($n[$key]))
        				$k = $n[$key];
        		} else if(is_object($n))
        		{
        			if(isset($n->$key))
        				$k = $n->$key;
        		}
        	}
        	if($k===false)
        		$k = $v;
        	$x = false;
        	if($val)
        	{
        		if(is_array($n))
        		{
        			if(isset($n[$val]))
        				$x = $n[$val];
        		} else if(is_object($n))
        		{
        			if(isset($n->$val))
        				$x = $n->$val;
        		}
        	}
        	if($x===false)
        		$x = $n;
            $rad .= "<input type='checkbox' name='$name' value='$k'";
            if(in_array($v,$selec))
                $rad .= " checked";
            $rad .= ">$x<br>";
        
        }
    else
        $rad .= "No options available<br>";
    return $rad;

}


function shrink($text, $max)
{
	if(strlen($text)<$max)
	{
		return substr($text, 0, strlen($text));
	} else {
		return substr($text, 0, $max)."...";
	}
	
}

function get_month_year()
{
	$date = getdate();
	$months = array(	1 => "Jan",
						2 => "Febr",
						3 => "Mar",
						4 => "Apr",
						5 => "May",
						6 => "Jun",
						7 => "Jul",
						8 => "Aug",
						9 => "Sep",
						10 => "Oct",
						11 => "Nov",
						12 => "Dec");
	$ret = null;
	$thismonth = $date["mon"];
	$thisyear = $date["year"]-2000;
	for($i=1;$i<=12;$i++)
	{
		
		$ret[$thismonth."_".$thisyear] = $months[$thismonth]." 0".$thisyear;
		if($thismonth==12)
		{
			$thismonth=1;
			$thisyear++; 
		}
		else $thismonth++;
	
	}
	return $ret;
}

function get_months()
{
	$months = array(	1 => "January",
						2 => "Februrary",
						3 => "March",
						4 => "April",
						5 => "May",
						6 => "June",
						7 => "July",
						8 => "August",
						9 => "September",
						10 => "October",
						11 => "November",
						12 => "December");
	return $months;
}

function get_days()
{
	$days = null;
	for($i=1;$i<32;$i++)
		$days[$i] = $i;
	return $days;
}
function get_years()
{
	$years = null;
	for($i=1930;$i<2011;$i++)
		$years[$i] = $i;
	return $years;

}

function get_hours()
{
	$hours = null;
	for($i=0;$i<24;$i++)
	{
		$j = ($i<10)? "0".$i:$i;
		$hours[$j] = $j;
	}
	return $hours;

}

function get_minutes()
{
	$minutes = null;
	for($i=0;$i<60;$i++)
	{
		$j = ($i<10)? "0".$i:$i;
		$minutes[$j] = $j;
	}
	return $minutes;

}

?>