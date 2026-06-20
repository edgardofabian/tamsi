<?php
class TamsiRoutes
{
    public $routes;
    public function __construct()
    {
        $paths = new Paths();
        $this->urls = array();        
    }

    public function processNiceUrls()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $path = trim($requestUri, '/');

        $dirs = explode('/',$path);

        foreach($this->urls as $nurl=>$url)
        {
            if (!(strpos($path,$nurl)===FALSE))
            {
                $params = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);

                if (((strlen($path)==0) && (!isset($params) || (strlen($params)>0))) || ((strlen($path)>0) && (strlen($nurl)==0)))
                {
                    //do not load params
                } else 
                {
                    $queryString = parse_url($url['query'], PHP_URL_QUERY);

                    parse_str($queryString, $queries);
                    
                    foreach($queries as $key=>$val)
                    {
                        $_GET[$key]=$val;
                        $_REQUEST[$key]=$val;
                    }
                    //echo '<!-- path='.$path.' nurl='.$nurl.' url='.json_encode($url).' queries='.json_encode($queries).'-->';    
                    
                    if (is_array($url->variables))
                    {
                        foreach($url->variables as $key=>$idx)
                        {
                            if (array_key_exists($idx,$dirs))
                            {
                                $_GET[$key]=$dirs[$idx];
                                $_REQUEST[$key]=$dirs[$idx];
                            }
                        }
                    }
                }
            }
        }
    
        //die(json_encode($_GET));
    }
}
