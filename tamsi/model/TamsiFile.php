<?php

class TamsiFile
{
    protected $filter;
    
    protected $size;

    protected $format;
    
    protected $path;
    
    protected $filename;
    

    
    protected $max_file_size;
    
    protected $ext;
    
    public function __construct()
    {
        $this->max_file_size=500000;
        $this->ext='';
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
    

 
    
    public function setSize($size)
    {
        $this->size = $size;
    }
    
    public function getSize()
    {
        return $this->size;
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
                        $msg.="Move upload file failed.";
                        $status=0;
                    }
                    
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
