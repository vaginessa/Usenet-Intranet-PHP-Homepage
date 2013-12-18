<?php

/* ==================================================================

    URL Joining
    Construct the URLs for each service based on configuration

================================================================== */

function getURL ($url,$port) {
    $host = explode('/',$url,2)[0];
    if ($port='80') {
    	$weburl = $host;
    } else {
        $weburl = $host.":".$port;
    }
    if ($host <> $url) {
    	$root = explode('/',$url,2)[1];
	$weburl = $weburl."/".$root;
    }
    return $weburl;
}
    # Sickbeard URL Joining
    if($config['sickbeardHTTPS']) {$sickbeardProtocol = "https";} else {$sickbeardProtocol = "http";}
    if($config['sickbeardUsername']) {
        $sickbeardURL = $sickbeardProtocol."://".$config['sickbeardUsername'].":".$config['sickbeardPassword']."@";
    } else {
        $sickbeardURL = $sickbeardProtocol."://";
    }
    $sickbeardURL = $sickbeardURL.getURL($config['sickbeardURL'],$config['sickbeardPort']);

    # Sabnzbd URL Joining
    if($config['sabnzbdHTTPS']) {$sabProtocol = "https";} else {$sabProtocol = "http";}
    if($config['sabnzbdUsername']) {
        $sabURL = $sabProtocol."://".$config['sabnzbdUsername'].":".$config['sabnzbdPassword']."@";
    } else {
        $sabURL = $sabProtocol."://";
    }
    $sabURL = $sabURL.getURL($config['sabnzbdURL'],$config['sabnzbdPort']);

    # Transmission Joining
    if($config['transmissionUsername']) {
        $transmissionURL = "http://".$config['transmissionUsername'].":".$config['transmissionPassword']."@";
    } else {
        $transmissionURL = "http://";
    }
    $transmissionURL = $transmissionURL.getURL($config['transmissionURL'],$config['transmissionPort']);

    # deluge Joining
    if($config['delugeUser']) {
        $delugeURL = "http://".$config['delugeUser'].":".$config['delugePass']."@";
    } else {
        $delugeURL = "http://";
    }
    $delugeURL = $delugeURL.getURL($config['delugeURL'],$config['delugePort']);

    # Headphones Joining
    if($config['headphonesHTTPS']) {$headphonesProtocol = "https";} else {$headphonesProtocol = "http";}
    $headphonesURL = $headphonesProtocol."://".getURL($config['headphonesURL'],$config['headphonesPort']);

    # Couchpotato Joining
    if($config['couchpotatoHTTPS']) {$couchpotatoProtocol = "https";} else {$couchpotatoProtocol = "http";}
    $couchpotatoURL = $couchpotatoProtocol."://".getURL($config['couchpotatoURL'],$config['couchpotatoPort']);

/* ==================================================================

    ByteSize Function
    http://www.phpfront.com/php/Convert-Bytes-to-corresponding-size/

================================================================== */

function ByteSize($bytes)  
    { 
    $size = $bytes / 1024; 
    if($size < 1024) 
        { 
        $size = number_format($size, 2); 
        $size .= ' KB'; 
        }  
    else  
        { 
        if($size / 1024 < 1024)  
            { 
            $size = number_format($size / 1024, 2); 
            $size .= ' MB'; 
            }  
        else if ($size / 1024 / 1024 < 1024)   
            { 
            $size = number_format($size / 1024 / 1024, 2); 
            $size .= ' GB'; 
            }  
        } 
    return $size; 
    } 

?>
