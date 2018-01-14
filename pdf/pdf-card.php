<?
require('fpdf.php');

setlocale (LC_ALL, '');
setlocale (LC_TIME, 'en_GB');

class PDF extends FPDF {

	var $angle=0;

   function ClippingText($x, $y, $txt, $outline=false)
    {
        $op=$outline ? 5 : 7;
        $this->_out(sprintf('q BT %.2F %.2F Td %d Tr (%s) Tj ET',
            $x*$this->k,
            ($this->h-$y)*$this->k,
            $op,
            $this->_escape($txt)));
    }

    function ClippingRect($x, $y, $w, $h, $outline=false)
    {
        $op=$outline ? 'S' : 'n';
        $this->_out(sprintf('q %.2F %.2F %.2F %.2F re W %s',
            $x*$this->k,
            ($this->h-$y)*$this->k,
            $w*$this->k,-$h*$this->k,
            $op));
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }

    function ClippingRoundedRect($x, $y, $w, $h, $r, $outline=false)
    {
        $k = $this->k;
        $hp = $this->h;
        $op=$outline ? 'S' : 'n';
        $MyArc = 4/3 * (sqrt(2) - 1);

        $this->_out(sprintf('q %.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r ;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r ;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out(' W '.$op);
    }

    function ClippingEllipse($x, $y, $rx, $ry, $outline=false)
    {
        $op=$outline ? 'S' : 'n';
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        $this->_out(sprintf('q %.2F %.2F m %.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k,($h-$y)*$k,
            ($x+$rx)*$k,($h-($y-$ly))*$k,
            ($x+$lx)*$k,($h-($y-$ry))*$k,
            $x*$k,($h-($y-$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k,($h-($y-$ry))*$k,
            ($x-$rx)*$k,($h-($y-$ly))*$k,
            ($x-$rx)*$k,($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k,($h-($y+$ly))*$k,
            ($x-$lx)*$k,($h-($y+$ry))*$k,
            $x*$k,($h-($y+$ry))*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c W %s',
            ($x+$lx)*$k,($h-($y+$ry))*$k,
            ($x+$rx)*$k,($h-($y+$ly))*$k,
            ($x+$rx)*$k,($h-$y)*$k,
            $op));
    }

    function ClippingCircle($x, $y, $r, $outline=false)
    {
        $this->ClippingEllipse($x, $y, $r, $r, $outline);
    }

    function ClippingPolygon($points, $outline=false)
    {
        $op=$outline ? 'S' : 'n';
        $h = $this->h;
        $k = $this->k;
        $points_string = '';
        for($i=0; $i<count($points); $i+=2){
            $points_string .= sprintf('%.2F %.2F', $points[$i]*$k, ($h-$points[$i+1])*$k);
            if($i==0)
                $points_string .= ' m ';
            else
                $points_string .= ' l ';
        }
        $this->_out('q '.$points_string . 'h W '.$op);
    }

    function UnsetClipping()
    {
        $this->_out('Q');
    }

    function ClippedCell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        if($border || $fill || $this->y+$h>$this->PageBreakTrigger)
        {
            $this->Cell($w,$h,'',$border,0,'',$fill);
            $this->x-=$w;
        }
        $this->ClippingRect($this->x,$this->y,$w,$h);
        $this->Cell($w,$h,$txt,'',$ln,$align,false,$link);
        $this->UnsetClipping();
    }
	
	function Rotate($angle,$x=-1,$y=-1)
	{
	    if($x==-1)
	        $x=$this->x;
	    if($y==-1)
	        $y=$this->y;
	    if($this->angle!=0)
	        $this->_out('Q');
	    $this->angle=$angle;
	    if($angle!=0)
	    {
	        $angle*=M_PI/180;
	        $c=cos($angle);
	        $s=sin($angle);
	        $cx=$x*$this->k;
	        $cy=($this->h-$y)*$this->k;
	        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
	    }
	}
	
	function _endpage()
	{
	    if($this->angle!=0)
	    {
	        $this->angle=0;
	        $this->_out('Q');
	    }
	    parent::_endpage();
	}
    function SetDrawColor() {
        //Set color for all stroking operations
        switch(func_num_args()) {
            case 1:
                $g = func_get_arg(0);
                $this->DrawColor = sprintf('%.3F G', $g / 100);
                break;
            case 3:
                $r = func_get_arg(0);
                $g = func_get_arg(1);
                $b = func_get_arg(2);
                $this->DrawColor = sprintf('%.3F %.3F %.3F RG', $r / 255, $g / 255, $b / 255);
                break;
            case 4:
                $c = func_get_arg(0);
                $m = func_get_arg(1);
                $y = func_get_arg(2);
                $k = func_get_arg(3);
                $this->DrawColor = sprintf('%.3F %.3F %.3F %.3F K', $c / 100, $m / 100, $y / 100, $k / 100);
                break;
            default:
                $this->DrawColor = '0 G';
        }
        if($this->page > 0)
            $this->_out($this->DrawColor);
    }

    function SetFillColor() {
        //Set color for all filling operations
        switch(func_num_args()) {
            case 1:
                $g = func_get_arg(0);
                $this->FillColor = sprintf('%.3F g', $g / 100);
                break;
            case 3:
                $r = func_get_arg(0);
                $g = func_get_arg(1);
                $b = func_get_arg(2);
                $this->FillColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
                break;
            case 4:
                $c = func_get_arg(0);
                $m = func_get_arg(1);
                $y = func_get_arg(2);
                $k = func_get_arg(3);
                $this->FillColor = sprintf('%.3F %.3F %.3F %.3F k', $c / 100, $m / 100, $y / 100, $k / 100);
                break;
            default:
                $this->FillColor = '0 g';
        }
        $this->ColorFlag = ($this->FillColor != $this->TextColor);
        if($this->page > 0)
            $this->_out($this->FillColor);
    }

    function SetTextColor() {
        //Set color for text
        switch(func_num_args()) {
            case 1:
                $g = func_get_arg(0);
                $this->TextColor = sprintf('%.3F g', $g / 100);
                break;
            case 3:
                $r = func_get_arg(0);
                $g = func_get_arg(1);
                $b = func_get_arg(2);
                $this->TextColor = sprintf('%.3F %.3F %.3F rg', $r / 255, $g / 255, $b / 255);
                break;
            case 4:
                $c = func_get_arg(0);
                $m = func_get_arg(1);
                $y = func_get_arg(2);
                $k = func_get_arg(3);
                $this->TextColor = sprintf('%.3F %.3F %.3F %.3F k', $c / 100, $m / 100, $y / 100, $k / 100);
                break;
            default:
                $this->TextColor = '0 g';
        }
        $this->ColorFlag = ($this->FillColor != $this->TextColor);
    }
    	
	function Print($x, $y, $text) {
		$this->Text($this->X + $x,$this->Y + $y,$text);
	}
	function PrintLn($text, $x = 0) {
		$this->Text($this->X + $x,$this->Y,$text);
		$this->NewLine();
	}
	
	function NewLine() {
		$this->Y+=$this->Lineheight;
		if($this->Y>285) {
			$this->NewColumn();
		}
	}
	
	function NewCard() {
		if(!$this->X) {
			$this->NewPage();
		} else {
			$this->X += $this->W+10;
			if($this->X>125) {
				$this->X = $this->LeftMargin;
				$this->Y += $this->H+$this->H+10;
			}
			if($this->Y>220) {
				$this->NewPage();
			}
		}
	}
	
	function NewPage() {
		global $translate;
		
		$this->AddPage();
		$this->X=$this->LeftMargin;
		$this->Y=$this->TopMargin;
		
	}

    //Cell with horizontal scaling if text is too wide
    function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
    {
        //Get string width
        $str_width=$this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if($w==0)
            $w = $this->w-$this->rMargin-$this->x;
        $ratio = ($w-$this->cMargin*2)/$str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit)
        {
            if ($scale)
            {
                //Calculate horizontal scaling
                $horiz_scale=$ratio*100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
            }
            else
            {
                //Calculate character spacing in points
                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align='';
        }

        //Pass on to Cell method
        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);

        //Reset character spacing/horizontal scaling
        if ($fit)
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
    }

    //Cell with horizontal scaling only if necessary
    function CellFitScale($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,false);
    }

    //Cell with horizontal scaling always
    function CellFitScaleForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,true,true);
    }

    //Cell with character spacing only if necessary
    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
    }

    //Cell with character spacing always
    function CellFitSpaceForce($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
    {
        //Same as calling CellFit directly
        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,true);
    }

    //Patch to also work with CJK double-byte text
    function MBGetStringLength($s)
    {
        if($this->CurrentFont['type']=='Type0')
        {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; $i++)
            {
                if (ord($s[$i])<128)
                    $len++;
                else
                {
                    $len++;
                    $i++;
                }
            }
            return $len;
        }
        else
            return strlen($s);
    }

}
function shorten($text, $length, $span = true, $isHTML = false)
{
	$i = 0;
	$suffix = '...';
	$tags = array();
	if($isHTML){
		preg_match_all('/<[^> ]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		foreach($m as $o){
			if($o[0][1] - $i >= $length)
				break;
			$t = utf8_substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
			if($t[0] != '/')
				$tags[] = $t;
			elseif(end($tags) == utf8_substr($t, 1))
				array_pop($tags);
			$i += $o[1][1] - $o[0][1];
		}
	}
	
	$output = str_replace(array("\n","\r","\t"),' ',$text);
	$output = utf8_substr($output, 0, $length = min(strlen($output),  $length + $i)) . (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
	
	if (strlen(strip_tags($output)) < strlen(strip_tags($text)))
	{
		$one = utf8_substr($output, 0, strrpos($output, " "));
		$two = utf8_substr($output, strrpos($output, " "), (strlen($output) - strrpos($output, " ")));
		preg_match_all('/<(.*?)>/s', $two, $tags);
		if (strlen($text) > $length) { $one .= $suffix; }
		$output = $one . implode($tags[0]);
	}
	
	if(strlen($text) > $length && $span){
		if($isHTML) $span = html_entity_decode(strip_tags($text)); else $span = $text;
		
		if($output=='...') $output = utf8_substr($text,0,$length).'...';

		return "<span title='".htmlentities($span,ENT_QUOTES)."'>".$output."</span>";
	} elseif(strlen($text) > $length) {
		return $output;
	}
	
	return $text;
}

function utf8_substr($str,$start) {
   preg_match_all("/./su", $str, $ar);

   if(func_num_args() >= 3) {
       $end = func_get_arg(2);
       return join("",array_slice($ar[0],$start,$end));
   } else {
       return join("",array_slice($ar[0],$start));
   }
}	

