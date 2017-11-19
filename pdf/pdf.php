<?
require('fpdf.php');

setlocale (LC_ALL, '');
setlocale (LC_TIME, 'en_GB');

class PDF extends FPDF {
	
	function Print($text, $x = 0) {
		$this->Text($this->X + $x,$this->Y,$text);
	}
	function PrintLn($text, $x = 0) {
		$this->Text($this->X + $x,$this->Y,$text);
		$this->NewLine();
	}
	
	function NewLine() {
		$this->Y+=5;
		if($this->Y>285) {
			$this->NewColumn();
		}
	}
	
	function NewColumn() {
		$this->X += $this->Column;
		$this->Y = $this->TopMargin;
		if($this->X>200-$this->LeftMargin) {
			$this->NewPage();
		}
	}
	
	function NewPage() {
		global $translate;
		
		$this->AddPage();
		$this->X=$this->LeftMargin;
		$this->Y=$this->TopMargin;
		
		$this->SetFont('helvetica','',7);
		$this->SetXY(9,285);
		$this->Cell(200,4,'Drop In The Ocean / '.$_SESSION['camp']['name'].' / '.$translate['listtitle_'.$_GET['title']].' List '.strftime('%A %e %B %Y %H:%M').' / Page '.$this->PageNo());
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

