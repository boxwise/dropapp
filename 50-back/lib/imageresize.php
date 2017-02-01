<?

/*
* There is still a lot to do in the naming and use of paths
*/


class imageResize {

	public function __construct() {

		$this->source = '';
		$this->sourcePath = '';
		$this->target = '';
		$this->targetPath = '';
		$this->targetPathinfo = '';
		$this->sourceInfo = '';
		$this->imageResource = '';
		$this->dest = '';
		$this->file = '';
		$this->x = 800;
		$this->x = 600;
		$this->resize = '';
		$this->permissions = 0666;
		$this->save_extension = true;
		$this->background = array(204,204,204);
		$this->scaleup = true;
		$this->quality = 100;
		$this->source_is_jpg = false;

	}

	public function imageResize() {

		if ($this->resize=='') {
			trigger_error('resize(): No resize property',E_USER_ERROR);
			die();
		}

		$this->setPaths();
		$this->checkMemNeeded();
		$this->cleanup();
		$this->imageResource();

		if(strtolower(substr($this->source,-4))=='.svg') {
			copy($this->source,$newfile.'.svg');
			chmod($newfile,$this->permissions);
			return;
		}

		if (substr($this->resize,0,1) == 'X') {
			$this->x = substr($this->resize,1);
			return $this->resizeX();
		} elseif (substr($this->resize,0,1) == 'Y') {
			$this->y = substr($this->resize,1);
			return $this->resizeY();
		} elseif (strpos($this->resize,',') && substr($this->resize,0,3)=='MAX') {
			$size = explode(',',substr($this->resize,3));
			$this->x = $size[0];
			$this->y = $size[1];
			$this->resizeMax();
		} elseif (strpos($this->resize,',')) {
			$size = explode(',',$this->resize);
			$this->x = $size[0];
			$this->y = $size[1];
			$this->resizeSquare();
		}
	}

    public function setPaths() {
    	$this->file = basename($this->source);
    	$this->sourceInfo = getimagesize($this->source);
    	$sourcePathinfo = pathinfo($this->source);
	    $this->sourcePath = $sourcePathinfo['dirname'].'/';
    	$targetPathinfo = pathinfo($this->target);
	    $this->targetPath = $targetPathinfo['dirname'].'/';
	    $this->targetPathinfo = pathinfo($this->target.substr($this->file,0,'.'));
	    $this->source_is_jpg = ($sourcePathinfo['extension'] == 'jpg' || $sourcePathinfo['extension'] == 'jpeg') ? true : false;
    }

	public function resizeX() {

		$this->y = ceil($this->x * ($this->sourceInfo[1] / $this->sourceInfo[0]));
		$this->dest = imagecreatetruecolor($this->x, $this->y);

		if($this->x == $this->sourceInfo[0] || ($this->y >= $this->sourceInfo[1] && !$this->scaleup)) {
			if ($this->source_is_jpg) {
				$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, 0, 0, $this->sourceInfo[0], $this->sourceInfo[1], $this->sourceInfo[0], $this->sourceInfo[1]);
				if(!$result) {
					@unlink($this->target);
					trigger_error('resizeX(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
					die();
				}
				$this->createJpeg();
			} else {
				copy($this->source,$this->target);
				chmod($this->target,$this->permissions);
			}
		} else {
			$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, 0, 0, $this->x+1, $this->y+1, $this->sourceInfo[0], $this->sourceInfo[1]);
			if(!$result) {
				@unlink($this->target);
				trigger_error('resizeX(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
				die();
			}
			$this->createJpeg();
		}
	}

	public function resizeY() {

		$this->x = ceil($this->y * ($this->sourceInfo[0] / $this->sourceInfo[1]));
		$this->dest = imagecreatetruecolor($this->x, $this->y);

		if($this->y == $this->sourceInfo[1] || ($this->x >= $this->sourceInfo[0] && !$this->scaleup)) {
			if ($this->source_is_jpg) {
				$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, 0, 0, $this->sourceInfo[0], $this->sourceInfo[1], $this->sourceInfo[0], $this->sourceInfo[1]);
				if(!$result) {
					@unlink($this->target);
					trigger_error('resizeY(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
					die();
				}
				$this->createJpeg();
			} else {
				copy($this->source,$this->target);
				chmod($this->target,$this->permissions);
			}
		} else {
			$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, 0, 0, $this->x+1, $this->y+1, $this->sourceInfo[0], $this->sourceInfo[1]);
			if(!$result) {
				@unlink($this->target);
				trigger_error('resizeY(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
				die();
			}
			$this->createJpeg();
		}

	}

	public function resizeMax() {

		$scalex = $this->x/$this->sourceInfo[0];
		$scaley = $this->y/$this->sourceInfo[1];

		if ($scalex<$scaley) {
			$this->dest = imagecreatetruecolor(intval($this->sourceInfo[0]*$scalex), intval($this->sourceInfo[1]*$scalex));
			$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, 0, 0, intval($this->sourceInfo[0]*$scalex), intval($this->sourceInfo[1]*$scalex), $this->sourceInfo[0], $this->sourceInfo[1]);
			if(!$result) {
				@unlink($this->target);
				trigger_error('resizeMax(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
				die();
			}
		} else {
			$this->dest = imagecreatetruecolor(intval($this->sourceInfo[0]*$scaley), intval($this->sourceInfo[1]*$scaley));
			$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, 0, 0, intval($this->sourceInfo[0]*$scaley), intval($this->sourceInfo[1]*$scaley), $this->sourceInfo[0], $this->sourceInfo[1]);
			if(!$result) {
				@unlink($this->target);
				trigger_error('resizeMax(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
				die();
			}
		}
		$this->createJpeg();
	}

	public function resizeSquare() {

		$this->dest = imagecreatetruecolor($this->x, $this->y);
		$scalex = $this->x/$this->sourceInfo[0];
		$scaley = $this->y/$this->sourceInfo[1];

		if($scalex==1 && $scaley==1) {
			//Upload image is already at defined size
			//If filetype is .jpg do a imagejpeg() here with defined quality setting (to prevent too large files)
			copy($this->source,$this->target);
			chmod($this->target,$this->permissions);
		} else {
			if (($scalex)>($scaley)) {
				$offsety = intval((($this->sourceInfo[1]*$scalex) - $this->y)/2 * (1/$scalex));
				$sizey = $this->sourceInfo[1] - (2* $offsety);
				$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, 0, $offsety, $this->x+1, $this->y+1, $this->sourceInfo[0], $sizey);
				if(!$result) {
					@unlink($this->target);
					trigger_error('resizeSquare(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
					die();
				}
			} else {
				$offsetx = intval((($this->sourceInfo[0]*$scaley) - $this->x)/2 * (1/$scaley));
				$sizex = $this->sourceInfo[0] - (2* $offsetx);
				$result = imagecopyresampled($this->dest, $this->imageResource, 0, 0, $offsetx, 0, $this->x+1, $this->y+1, $sizex, $this->sourceInfo[1]);
				if(!$result) {
					@unlink($this->target);
					trigger_error('resizeSquare(): Image could not be scaled - imagecopyresampled() failed',E_USER_ERROR);
					die();
				}
			}
			$this->createJpeg();
		}
	}

    public function setValue($name,$value) {
    	if ($name) {
    		$this->$name = $value;
    	}
    }

    public function getValue($value) {
    	return $this->$value;
    }

    private function imageResource() {
		switch ($this->sourceInfo[2]) {
			case 1: $this->imageResource = imagecreatefromgif($this->source); break;
			case 2: $this->imageResource = imagecreatefromjpeg($this->source); break;
			case 3: $this->imageResource = imagecreatefrompng($this->source); break;
		}
		if(!$this->imageResource) {
			@unlink($this->target);
			trigger_error('Unsupported file format ('.$this->source.')',E_USER_ERROR);
			die();
		}
    }

    private function cleanup() {
		$deletethese = glob($this->targetPathinfo['dirname'].'/'.$this->targetPathinfo['filename'].'.*');
		foreach($deletethese as $deletethis) @unlink($deletethis);
    }

    private function checkMemNeeded() {
		$memneeded = intval($this->sourceInfo[0]*$this->sourceInfo[1]*8);
		if($memneeded > $this->let_to_num(ini_get('memory_limit'))) {
			@unlink($this->target);
			trigger_error('Image could not be scaled - Exceeds memory limit',E_USER_ERROR);
			die();
		}
    }

	private function createJpeg() {
		$newfile = $this->targetPathinfo['dirname'].'/'.$this->targetPathinfo['filename'].'.jpg';
		$result = imagejpeg($this->dest,$newfile,$this->quality);
		chmod($newfile,$this->permissions);
		if(!$result) {
			@unlink($newfile);
			trigger_error('createJpeg(): Image could not be created - imagejpeg() failed',E_USER_ERROR);
			die();
		}
	}

	private function let_to_num($v){ //This function transforms the php.ini notation for numbers (like '2M') to an integer (2*1024*1024 in this case)
	    $l = substr($v, -1);
	    $ret = substr($v, 0, -1);
	    switch(strtoupper($l)){
	    case 'P':
	        $ret *= 1024;
	    case 'T':
	        $ret *= 1024;
	    case 'G':
	        $ret *= 1024;
	    case 'M':
	        $ret *= 1024;
	    case 'K':
	        $ret *= 1024;
	        break;
	    }
	    return $ret;
	}

}