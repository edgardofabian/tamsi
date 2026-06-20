<?php
ini_set('display_errors', 'On');
//error_reporting(E_ALL ^ E_WARNING); 
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
require_once 'model/Shape.php';

function getMidpointLength($x1,$y1,$x2,$y2)
{
    
    $dist = sqrt(($x2-$x1)*($x2-$x1)+($y2-$y1)*($y2-$y1));
    
    $ret = array('x'=>$x1+(0.5*($x2-$x1)),'y'=>($y2+0.5*($y1-$y2)),'len'=>$dist);
    
    return $ret;
}


if (isset($_REQUEST['width']) && isset($_REQUEST['height']))
{
    $width=$_REQUEST['width'];
    $height=$_REQUEST['height'];
    # new image...
    $img = imagecreatetruecolor($width, $height);
    $shapes = array();
    if (isset($_REQUEST['vertices']))
    {
        $shape = new Shape();
        $shape->name = "polygon";
        $shape->color = json_decode($_REQUEST['color']);
        $shape->vertices=json_decode($_REQUEST['vertices']);
        $shape->length_labels=json_decode($_REQUEST['length_labels']);
        $shape->length_label_unit=$_REQUEST['length_label_unit'];
        $shapes[] = $shape;
        //die('test1='.json_encode($shapes));
    } else
    {
      $shapes = json_decode($_REQUEST['shapes']);
      //die(json_last_error().json_last_error_msg());
    }
    
    foreach($shapes as $shape)
    {
      if (strtolower(trim($shape->name)) === 'polygon')
      {   
        //die(json_encode($length_labels));
        $num_points=(int)(count($shape->vertices)/2);
        //die(json_encode($shape));
        
        ## colors
        $white = imagecolorallocate($img, 255, 255, 255);
        $pen = imagecolorallocate($img,$shape->color[0], $shape->color[1], $shape->color[2]);
        imagepolygon ( $img , $shape->vertices , $num_points , $pen );
        
        for($i=0;$i<$num_points;$i++)
        {
            if ($shape->length_labels[$i])
            {
              
                $off = ($i)*2;
                $x1o=$off;
                $y1o=$off+1;
                if (($off+2)>(count($shape->vertices)-1))
                {
                    $x2o=0;
                    $y2o=1;
                } else
                {
                    $x2o=$off+2;
                    $y2o=$off+3;
                }
                $mid_len = getMidpointLength($shape->vertices[$x1o],$shape->vertices[$y1o],$shape->vertices[$x2o],$shape->vertices[$y2o]);
                //die(json_encode($mid_len));
                if (((int)$shape->length_labels[$i])==1)
                {
                  imagestring($img,5, $mid_len['x'],$mid_len['y'], ((int)$mid_len['len']).$shape->length_label_unit, $white);
                } else
                {
                  imagestring($img,5, $mid_len['x'],$mid_len['y'], $shape->length_labels[$i], $white);
                }
            }
        }
      } else if (strtolower(trim($shape->name)) === 'line')
      {   
        //die(json_encode($length_labels));
        $num_points=(int)(count($shape->vertices)/2);
        //die(json_encode($shape));
        
        ## colors
        $white = imagecolorallocate($img, 255, 255, 255);
        $pen = imagecolorallocate($img,$shape->color[0], $shape->color[1], $shape->color[2]);
        imageline ( $img , $shape->vertices[0] , $shape->vertices[1] , $shape->vertices[2] , $shape->vertices[3] , $pen );        
        
        $mid_len = getMidpointLength($shape->vertices[0],$shape->vertices[1],$shape->vertices[2],$shape->vertices[3]);
        //die(json_encode($mid_len));
        if (((int)$shape->length_labels[0])==1)
        {
          imagestring($img,5, $mid_len['x'],$mid_len['y'], ((int)$mid_len['len']).$shape->length_label_unit, $white);
        } else
        {
          imagestring($img,5, $mid_len['x'],$mid_len['y'], $shape->length_labels[0], $white);
        }
        
      }
    }
} 
else
{
    $img = imagecreatetruecolor(80, 80);
    $black = imagecolorallocate($img, 0, 0, 0);
    $white = imagecolorallocate($img, 255, 255, 255);
    imagestring($img,5, 20, 35, 'NONE', $white);
    
}

header("Content-type: image/png");
imagepng($img);
imagedestroy($img);
?>
