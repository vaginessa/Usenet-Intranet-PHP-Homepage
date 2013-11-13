<?php
#
# Sample Socket I/O to BFGMiner API
#
class bfgminer {
public $url = "";
public $port = "4028";
function getsock($addr, $port)
{
 $socket = null;
 $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
 if ($socket === false || $socket === null)
 {
	$error = socket_strerror(socket_last_error());
	$msg = "socket create(TCP) failed";
	echo "ERR: $msg '$error'\n";
	return null;
 }

 $res = socket_connect($socket, $addr, $port);
 if ($res === false)
 {
	$error = socket_strerror(socket_last_error());
	$msg = "socket connect($addr,$port) failed";
	echo "ERR: $msg '$error'\n";
	socket_close($socket);
	return null;
 }
 return $socket;
}
#
# Slow ...
function readsockline($socket)
{
 $line = '';
 while (true)
 {
	$byte = socket_read($socket, 1);
	if ($byte === false || $byte === '')
		break;
	if ($byte === "\0")
		break;
	$line .= $byte;
 }
 return $line;
}
#
function request($cmd)
{
 $socket = $this->getsock($this->url, $this->port);
 if ($socket != null)
 {
	socket_write($socket, $cmd, strlen($cmd));
	$line = $this->readsockline($socket);
	socket_close($socket);

	if (strlen($line) == 0)
	{
		echo "WARN: '$cmd' returned nothing\n";
		return $line;
	}

	#print "$cmd returned '$line'\n";

	if (substr($line,0,1) == '{')
		return json_decode($line, true);

	$data = array();

	$objs = explode('|', $line);
	foreach ($objs as $obj)
	{
		if (strlen($obj) > 0)
		{
			$items = explode(',', $obj);
			$item = $items[0];
			$id = explode('=', $items[0], 2);
			if (count($id) == 1 or !ctype_digit($id[1]))
				$name = $id[0];
			else
				$name = $id[0].$id[1];

			if (strlen($name) == 0)
				$name = 'null';

			if (isset($data[$name]))
			{
				$num = 1;
				while (isset($data[$name.$num]))
					$num++;
				$name .= $num;
			}

			$counter = 0;
			foreach ($items as $item)
			{
				$id = explode('=', $item, 2);
				if (count($id) == 2)
					$data[$name][$id[0]] = $id[1];
				else
					$data[$name][$counter] = $id[0];

				$counter++;
			}
		}
	}

	return $data;
 }

 return null;
}
#
public function __construct ( $url = "127.0.0.1", $port = 4028) {
    // server url
        $this->url = $url;
	$this->port = $port;

}
function get() {
#if (isset($argv) and count($argv) > 1)
# $r = request($argv[1]);
#else
 $r = $this->request('summary');
#
$values = array("MHS av", "MHS 5s", "Found Blocks", "Stale");
$string = "";
foreach ($values as $val)
{
$string = $string.PHP_EOL."<li><strong class='showname'>".$val."</strong><br />".PHP_EOL."<span class='showep'>";
$string = $string.$r['SUMMARY'][$val]."</span></li>";
}
return $string;
#echo "<pre>".print_r($r, true)."\n</pre>";
#
}}
?>
