<?php

require 'fpdf.php';

setlocale(LC_ALL, '');
setlocale(LC_TIME, 'en_GB');

class PDF extends FPDF
{
    public function Print($text, $x = 0)
    {
        $this->Text($this->X + $x, $this->Y, $text);
    }

    // related to this trello: https://trello.com/c/OjWZzsGA
    public function Footer()
    {
        // Go to 10 cm from bottom
        $this->SetY(-10);
        // Set font to Open Sans
        $this->SetFont('opensans', 'I', 6);
        // Print centered page number with datetime  and timezone
        $this->AliasNbPages('{totalPages}');
        // The timezone of the user is retrieved from their Auth0 user's profile if available, otherwise the server time zone is used
        // related to this trello card https://trello.com/c/OjWZzsGA
        $timezone = ($_SESSION['auth0_user']['https://www.boxtribute.com/timezone']) ? $_SESSION['auth0_user']['https://www.boxtribute.com/timezone'] : date_default_timezone_get();

        $dt = new DateTime('now', new DateTimeZone($timezone));

        $this->Cell(0, 10, 'Page '.$this->PageNo().' of {totalPages} Printed on '.$dt->format('d-m-Y H:i:s')." {$timezone}", 0, 0, 'C');
    }

    public function PrintLn($text, $x = 0)
    {
        $this->Text($this->X + $x, $this->Y, $text);
        $this->NewLine();
    }

    public function NewLine()
    {
        $this->Y += $this->Lineheight;
        if ($this->Y > 285) {
            $this->NewColumn();
        }
    }

    public function NewColumn($lastcontainer = '')
    {
        $this->haspage = true;
        $this->X += $this->Column;
        $this->Y = $this->TopMargin;
        if ($this->X > 210 - $this->LeftMargin - $this->Column) {
            $this->NewPage($lastcontainer);
        }
    }

    public function NewPage($lastcontainer = '')
    {
        global $translate;

        $this->AddPage();
        $this->haspage = true;
        $this->X = $this->LeftMargin;
        $this->Y = $this->TopMargin;

        $this->SetFont('helvetica', '', 7);
        $this->SetXY($this->LeftMargin - 1, 285);
        $this->Cell(200, 4, $_SESSION['organisation']['label'].' / '.$_SESSION['camp']['name'].' / '.strftime('%A %e %B %Y %H:%M').' / Page '.$this->PageNo());
        $this->setXY(210 - $this->LeftMargin - 5, 284);
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(5, 4, $lastcontainer);
    }

    //Cell with horizontal scaling if text is too wide
    public function CellFit($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $scale = false, $force = true)
    {
        //Get string width
        $str_width = $this->GetStringWidth($txt);

        //Calculate ratio to fit cell
        if (0 == $w) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $ratio = ($w - $this->cMargin * 2) / $str_width;

        $fit = ($ratio < 1 || ($ratio > 1 && $force));
        if ($fit) {
            if ($scale) {
                //Calculate horizontal scaling
                $horiz_scale = $ratio * 100.0;
                //Set horizontal scaling
                $this->_out(sprintf('BT %.2F Tz ET', $horiz_scale));
            } else {
                //Calculate character spacing in points
                $char_space = ($w - $this->cMargin * 2 - $str_width) / max($this->MBGetStringLength($txt) - 1, 1) * $this->k;
                //Set character spacing
                $this->_out(sprintf('BT %.2F Tc ET', $char_space));
            }
            //Override user alignment (since text will fill up cell)
            $align = '';
        }

        //Pass on to Cell method
        $this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

        //Reset character spacing/horizontal scaling
        if ($fit) {
            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
        }
    }

    //Cell with horizontal scaling only if necessary
    public function CellFitScale($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, true, false);
    }

    //Cell with horizontal scaling always
    public function CellFitScaleForce($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, true, true);
    }

    //Cell with character spacing only if necessary
    public function CellFitSpace($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, false, false);
    }

    //Cell with character spacing always
    public function CellFitSpaceForce($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        //Same as calling CellFit directly
        $this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, false, true);
    }

    //Patch to also work with CJK double-byte text
    public function MBGetStringLength($s)
    {
        if ('Type0' == $this->CurrentFont['type']) {
            $len = 0;
            $nbbytes = strlen($s);
            for ($i = 0; $i < $nbbytes; ++$i) {
                if (ord($s[$i]) < 128) {
                    ++$len;
                } else {
                    ++$len;
                    ++$i;
                }
            }

            return $len;
        }

        return strlen($s);
    }
}
function shorten($text, $length, $span = true, $isHTML = false)
{
    $i = 0;
    $suffix = '...';
    $tags = [];
    if ($isHTML) {
        preg_match_all('/<[^> ]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        foreach ($m as $o) {
            if ($o[0][1] - $i >= $length) {
                break;
            }
            $t = utf8_substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
            if ('/' != $t[0]) {
                $tags[] = $t;
            } elseif (end($tags) == utf8_substr($t, 1)) {
                array_pop($tags);
            }
            $i += $o[1][1] - $o[0][1];
        }
    }

    $output = str_replace(["\n", "\r", "\t"], ' ', $text);
    $output = utf8_substr($output, 0, $length = min(strlen($output), $length + $i)).(count($tags = array_reverse($tags)) ? '</'.implode('></', $tags).'>' : '');

    if (strlen(strip_tags($output)) < strlen(strip_tags($text))) {
        $one = utf8_substr($output, 0, strrpos($output, ' '));
        $two = utf8_substr($output, strrpos($output, ' '), (strlen($output) - strrpos($output, ' ')));
        preg_match_all('/<(.*?)>/s', $two, $tags);
        if (strlen($text) > $length) {
            $one .= $suffix;
        }
        $output = $one.implode($tags[0]);
    }

    if (strlen($text) > $length && $span) {
        if ($isHTML) {
            $span = html_entity_decode(strip_tags($text));
        } else {
            $span = $text;
        }

        if ('...' == $output) {
            $output = utf8_substr($text, 0, $length).'...';
        }

        return "<span title='".htmlentities($span, ENT_QUOTES)."'>".$output.'</span>';
    }
    if (strlen($text) > $length) {
        return $output;
    }

    return $text;
}

function utf8_substr($str, $start)
{
    preg_match_all('/./su', $str, $ar);

    if (func_num_args() >= 3) {
        $end = func_get_arg(2);

        return join('', array_slice($ar[0], $start, $end));
    }

    return join('', array_slice($ar[0], $start));
}
