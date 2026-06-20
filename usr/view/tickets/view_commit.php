<?php
$commit=$_GET['commit'];
$tid=$_GET['tid'];
$dir=__DIR__.'/../../../';
if (file_exists($dir.'.git'))
{
    function execute($cmd, $workdir = null) {

        if (is_null($workdir)) {
            $workdir = __DIR__;
        }

        $descriptorspec = array(
           0 => array("pipe", "r"),  // stdin
           1 => array("pipe", "w"),  // stdout
           2 => array("pipe", "w"),  // stderr
        );

        $process = proc_open($cmd, $descriptorspec, $pipes, $workdir, null);

        $stdout = stream_get_contents($pipes[1]);
        
        if (strlen($stdout)>50000) 
        {
            $stdout = substr($stdout,0,50000)."\r\n ....truncated! too long";
        }
        
        fclose($pipes[1]);

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        return [
            'code' => proc_close($process),
            'out' => trim($stdout),
            'err' => trim($stderr),
        ];
    }
}

if (file_exists($dir.'.git'))
{
    $output = array();
    
    $git_cmd='git diff '.$commit.'~ '.$commit;
    $output=execute($git_cmd,$dir);
    $out=htmlentities($output['out']);
    
    echo '<p>Changes:</p>';
    echo '<pre>';
    echo $out;
    echo '</pre>';
    
    
}
