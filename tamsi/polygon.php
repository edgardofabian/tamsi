<?php
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
    
    
    $color = json_decode($_REQUEST['color']);
    $vertices=json_decode($_REQUEST['vertices']);
    $length_labels=json_decode($_REQUEST['length_labels']);
    //die(json_encode($length_labels));
    $num_points=(int)(count($vertices)/2);
    # new image...
    $img = imagecreatetruecolor($width, $height);
    
    ## colors
    $white = imagecolorallocate($img, 255, 255, 255);
    $pen = imagecolorallocate($img, $color[0], $color[1], $color[2]);
    imagepolygon ( $img , $vertices , $num_points , $pen );
    
    for($i=0;$i<$num_points;$i++)
    {
        if ($length_labels[$i])
        {
            $off = ($i)*2;
            $x1o=$off;
            $y1o=$off+1;
            if (($off+2)>(count($vertices)-1))
            {
                $x2o=0;
                $y2o=1;
            } else
            {
                $x2o=$off+2;
                $y2o=$off+3;
            }
            $mid_len = getMidpointLength($vertices[$x1o],$vertices[$y1o],$vertices[$x2o],$vertices[$y2o]);
            //die(json_encode($mid_len));
            imagestring($img,5, $mid_len['x'],$mid_len['y'], (int)($mid_len['len']), $white);
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
