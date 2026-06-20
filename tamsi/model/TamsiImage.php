<?php
class TamsiImage 
{
    protected $filter;
    
    protected $width;

    protected $height;
    
    protected $path;
    
    protected $filename;
    

    protected $resize_method; 
    
    protected $max_file_size;
    
    protected $ext;
    
    public function __construct()
    {
        $this->resize_method ="";
        $this->max_file_size=2000000;
        $this->ext='';
    }
     
    
    public function getDimensions()
    {
        $ext = strtolower(strrchr($this->getFilename(), '.'));
        
        $file = $this->getAbsolutePath();
        $size = getimagesize($file);
        if ($size)
        {
            $this->width=$size[0];
            $this->height=$size[1];
        }
    }

    public function setFilter($filter)
    {
        $this->filter = $filter;
    }
    

    public function getFilter()
    {
        return $this->filter;
    }
    
    public function setFilename($name)
    {
        $this->filename = $name;
    }
    
    public function setExt($ext)
    {
		$this->ext = $ext;
		return $this;
	}

    public function getFilename()
    {
        return $this->filename;
    }
    

    public function setPath($path)
    {
		$this->path=$path;
        return $this;
	}

    public function getPath()
    {
        return $this->path;
    }
  
    public function getAbsolutePath()
    {
        if ($this->filename)
        {
            return $this->path.$this->filename;
        } 
        else
        {
            return $this->path.self::PATH_TO_NONE;
        }
    }
    
    public function setResizeMethod($method)
    {
        $this->resize_method = $method;
    }
    
    public function getResizeMethod()
    {
        return $this->resize_method;
    }
    
    public function setWidth($width)
    {
        $this->width = $width;
    }
    
    public function getWidth()
    {
        return $this->width;
    }    
    
    public function setHeight($height)
    {
        $this->height = $height;
    }
    
    public function getHeight()
    {
        return $this->height;
    }    
	

    public function resize($method="force")
    {
        //openfile and get imagewidth and imageheight
        // *** Get extension
        $ext = strtolower(strrchr($this->getFilename(), '.'));
        
        $file = $this->getAbsolutePath();
        switch($ext)
        {
            case '.jpg':
            case '.jpeg':
                $img = imagecreatefromjpeg($file);
                //var_dump($img);
                break;
            case '.gif':
                $img = imagecreatefromgif($file);
                break;
            case '.png':
                $img = imagecreatefrompng($file);
                break;
            default:
                return false;
        }
    
        $name = substr($this->filename,0,strpos(strtolower($this->filename),$ext));
        $image_width = imagesx($img);
        $image_height = imagesy($img);
        $aspect_ratio = $image_width / $image_height;
        $width = $image_width;
        $height = $image_height;
        //get optimal width or height based on options
        switch ($method)
        {
            case 'force_width':
                $width = $this->width;
                $height = $this->width  / $aspect_ratio;
                break;
            case 'force_height':
                $height = $this->height;
                $width = $this->height * $aspect_ratio;
                break;
            case 'force';
                $width = $this->width;
                $height = $this->height; 
                break;
            default: //keep image size
                break;
        }
        //die('width:'.$image_width.' height:'.$height.' file:'.$file.' ext:'.$ext);
        // create image canvas of x, y size
        $imageResized = imagecreatetruecolor($width, $height);
        //resample to get new resize image
        imagecopyresampled($imageResized, $img, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
        
        $image_quality_inverted = 2; // 0 best 9 worst
        
        
        // delete the old image
        unlink($this->getAbsolutePath());
        
        //clear memory of old image
        imagedestroy($img);
        
        

        $this->ext = '.png';
		$this->filename = $name.$this->ext;
            
        //$this->filename = $name.$ext;
        
        //store the resize image
        switch($this->ext)
        {
            case '.jpg':
            case '.jpeg':
                imagejpeg($imageResized, $this->getAbsolutePath(), 100-10*$image_quality_inverted);
                break;
            case '.gif':
                imagegif($imageResized, $this->getAbsolutePath(), 100-10*$image_quality_inverted);
                break;
            case '.png':
                imagepng($imageResized, $this->getAbsolutePath(), $image_quality_inverted);
                break;
            default:
                return false;
        }
        
        
        //refresh new width/height
        $this->width = $width;
        $this->height = $height;
        $this->resize_method = "";
        
        imagedestroy($imageResized);
    }
    
    public function createIcon($width,$height,$fname)
    {
        //openfile and get imagewidth and imageheight
        // *** Get extension
        $ext = strtolower(strrchr($this->getFilename(), '.'));
        
        $file = $this->getAbsolutePath();
        switch($ext)
        {
            case '.jpg':
            case '.jpeg':
                $img = imagecreatefromjpeg($file);
                break;
            case '.gif':
                $img = imagecreatefromgif($file);
                break;
            case '.png':
                $img = imagecreatefrompng($file);
                break;
            default:
                return false;
        }
    
        $name = substr($this->filename,0,strpos(strtolower($this->filename),$ext));
        $image_width = imagesx($img);
        $image_height = imagesy($img);
        $aspect_ratio = $image_width / $image_height;
        //get optimal width or height based on options
		if ($image_width > $image_height)
		{
			$height = $width  / $aspect_ratio;
		}
		else
		{
			$width = $height * $aspect_ratio;
		}
        
        // create image canvas of x, y size
        $imageResized = imagecreatetruecolor($width, $height);
        //resample to get new resize image
        imagecopyresampled($imageResized, $img, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
        
        $image_quality_inverted = 0; // 0 best 9 worst
        
        
        
        //clear memory of old image
        imagedestroy($img);
        
        
		$this->filename = $fname;
        //$this->filename = $name.$ext;
        
        //store the resize image
        switch($ext)
        {
            case '.jpg':
            case '.jpeg':
                imagejpeg($imageResized, $this->getAbsolutePath(), 100-10*$image_quality_inverted);
                break;
            case '.gif':
                imagegif($imageResized, $this->getAbsolutePath(), 100-10*$image_quality_inverted);
                break;
            case '.png':
                imagepng($imageResized, $this->getAbsolutePath(), $image_quality_inverted);
                break;
            default:
                return false;
        }
        
        
        //refresh new width/height
        $this->width = $width;
        $this->height = $height;
        $this->resize_method = "";
        
        imagedestroy($imageResized);
    }
    
    public function save()
    {
        if ( ($_FILES["file"]["size"] < $this->max_file_size))
        {
            if ($_FILES["file"]["error"] > 0)
            {
                $msg="Return Code: " . $_FILES["file"]["error"] . "<br />";
                $status= 0;
            }	else
            {
                $fullpath = $this->path;
                
                /*if (file_exists($fullpath.$_FILES["file"]["name"]))
                {
                    if ($status==0)
                    {
                        $msg=$_FILES["file"]["name"] . " already exists. ";
                        $status=1;
                    } else 
                    {
                        $status=2; $back=2;
                    }
                } else*/ 
                $status=2;
                if($status==2)	
                {
                    $msg="Upload: " . $_FILES["file"]["name"] . "<br />";
                    $msg.="Type: " . $_FILES["file"]["type"] . "<br />";
                    $msg.="Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                    $msg.="Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";
                    
                    
                    if (!file_exists($fullpath)) 
                    {
                        $msg.="creating $fullpath<br>";
                        mkdir("$fullpath", 0777, true);
                    }

                    if (copy($_FILES["file"]["tmp_name"],$fullpath.$this->filename))
                    {
                        
                        $status=2;
                        $msg.="Stored in: " . $fullpath . $this->filename;
                    } else 
                    {
                        $msg.="Move upload to ".$fullpath.$this->filename." failed.";
                        $status=0;
                    }
                    $this->getDimensions();
                }
            }
        } else
        {
          $msg="File greater than ".$this->max_file_size." bytes";
          $status = 0;
        }
        $_SESSION['tamsi_notice']="$msg!";
        return $status;
    }
}
