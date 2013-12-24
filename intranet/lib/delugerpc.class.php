<?php
#using this project
#https://github.com/matuck/DelugeApi
include ('DelugeApi/Client/DelugeNamespace.php');
include ('DelugeApi/Client/Command.php');
include ('DelugeApi/Client/Client.php');
include ('DelugeApi/Client/HTTPClient.php');
include ('DelugeApi/Client/Server.php');
include ('DelugeApi/Client/Response.php');
include ('DelugeApi/Exception/DelugeException.php');
include ('DelugeApi/Exception/DelugeRequestException.php');
include ('DelugeApi/Exception/DelugeInvalidCommandException.php');
include ('DelugeApi/Exception/DelugeInvalidNamespaceException.php');
include ('DelugeApi/Exception/DelugeServerException.php');   


class DelugeRPC {
  public $password = '';
  public $username = '';
  public $port = 8112;
  public $url = "http://127.0.0.1";
  
  public function __construct( $url = 'localhost', $port = '8112', $username = null, $password = null ) {
    // server URL
    $this->url = $url;
    $this->port = $port;

    // Username & password
    $this->username = $username;
    $this->password = $password;
  }

  public function connect () {
    $params = array(
      'host' => $this->url,
      'port' => $this->port,
      'user' => $this->username,
      'pass' => $this->password
      );
    try {
      $client = new matuck\DelugeApi\Client\HTTPClient($params);
    } catch (ConnectionException $e) {
      die($e->getMessage());
    }

    return $client;
  }

  public function get () {
    $torrent_array = array();
    $client = $this->connect();
    $torrent_ids = $client->core->get_session_state();
    foreach ($torrent_ids as &$torrent) {
      #print_r($client->core->get_torrent_status($torrent,['name','size','paused','ratio','progress','total_size','total_done','download_payload_rate']));
      array_push($torrent_array, $client->core->get_torrent_status($torrent,['name','size','paused','ratio','progress','total_size','total_done','download_payload_rate']));
    }
    #print "<pre>";
    #print_r ($torrent_array);
    #print "</pre>";
    return $torrent_array;
  }
}

?>

