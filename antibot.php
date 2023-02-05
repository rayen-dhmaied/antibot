<?php
function get_IP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];
	if (filter_var($client,FILTER_VALIDATE_IP))
    return $client;
    elseif (filter_var($forward,FILTER_VALIDATE_IP))
    return $forward;
    else
    return $remote;
}

function block($block_type,$proof='')
{
	if($block_type == 'remote_address')
		$new_line= "[".date('Y-m-d H:i:s')."] ".$GLOBALS['ipAddress'].' Blocked because of Bad IP'.$proof.PHP_EOL;
	if($block_type == 'hostname')
		$new_line= "[".date('Y-m-d H:i:s')."] ".$GLOBALS['ipAddress'].' Blocked because of Bad Hostname - '.$proof.PHP_EOL;
	if($block_type == 'useragent')
		$new_line= "[".date('Y-m-d H:i:s')."] ".$GLOBALS['ipAddress'].' Blocked because of Bad UserAgent - '.$proof.PHP_EOL;
	file_put_contents('blocked_IPs.txt',$new_line,FILE_APPEND);
	header('HTTP/1.0 403 Forbidden');
	header('Location: https://www.github.com');
	exit();
}

function scan_IP()
{
	if ($file = fopen('remote_address.txt','r'))
	{
		while(!feof($file)) 
		{
			$line = chop(fgets($file));
			if(strpos($GLOBALS['ipAddress'],$line) === 0)
			{
				fclose($file);
				block('remote_address');
			}
		}
		fclose($file);
	}
}

function multi_scan($scan_type)
{
	if ($scan_type == 'hostname')
		$sample=$GLOBALS['hostname'];
	else
		$sample=$_SERVER['HTTP_USER_AGENT'];
	$data_path=$scan_type.'.txt';
	if ($file = fopen($data_path,'r'))
	{
		while(!feof($file)) 
		{
			$line = chop(fgets($file));
			if(strpos($sample,$line) !== false)
			{
				fclose($file);
				if($scan_type == 'useragent')
					$sample=$line;
				block($scan_type,$sample);
			}
		}
		fclose($file);
	}	
}

$ipAddress=get_IP();
scan_IP();
$hostname=gethostbyaddr($ipAddress);
if($hostname != $ipAddress)
	multi_scan('hostname');
if(!empty($_SERVER['HTTP_USER_AGENT']))
	multi_scan('useragent');
?>