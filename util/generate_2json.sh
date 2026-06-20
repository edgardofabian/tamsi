<?php

if (count($argv)>3)
{
	$json_1=array();
	$json_2=array();
	$json = array();
	if (file_exists($argv[1]))
	{
		$data = file_get_contents($argv[1]);
		$group_sep = "\n";
		$datas = explode($group_sep,$data);
		foreach($datas as $grp)
		{
			$parts = explode($argv[2],$grp);
			$json[trim($parts[0])]=trim($parts[1]);
			
		}
		$file1=str_replace('.txt','_1.json',$argv[1]);
		$file2=str_replace('.txt','_2.json',$argv[1]);
		$json_1 = array_keys($json);
		foreach($json_1 as $key)
		{
			$json_2[]=$json[$key];
		}
		file_put_contents($file1,json_encode($json_1));
		file_put_contents($file2,json_encode($json_2));
		echo "Writen results to $file1 and $file2\n";
	}
} else
{
	echo "generate_2json.php source_file type_separator group_separator \r\n";
	echo "generate_2json.php file.txt - \n \r\n";
}
