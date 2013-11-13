<?php 
	$config = array(

		# Services
		# Set these to false to disable them
		"sickbeard" => true,
		"couchpotato" => true,
		"headphones" => true,
		"sabnzbd" => true,
		"uTorrent" => false,
		"transmission" => true,

		# URLs and Ports
		"sickbeardURL" => "$_SERVER[HTTP_HOST]",
		"sickbeardPort" => "8081",
		"sickbeardAPI" => "",
		"sickbeardHTTPS" => false,
		"sabnzbdURL" => "$_SERVER[HTTP_HOST]",
		"sabnzbdPort" => "8080",
		"sabnzbdAPI" => "",
		"sabnzbdHTTPS" => false,
		"couchpotatoURL" => "$_SERVER[HTTP_HOST]",
		"couchpotatoPort" => "5000",
		"couchpotatoHTTPS" => false,
		"headphonesURL" => "$_SERVER[HTTP_HOST]",
		"headphonesPort" => "8181",
		"headphonesHTTPS" => false,
		"uTorrentURL" => "$_SERVER[HTTP_HOST]",
		"uTorrentPort" => "8089",
		"transmissionURL" => "$_SERVER[HTTP_HOST]",
		"transmissionPort" => "9091",

		# Usernames and Passwords
		# If not using usernames or passwords, leave these to false.
		# ie. "sickbeardUsername" => false,
		"sickbeardUsername" => false,
		"sickbeardPassword" => false,
		"sabnzbdUsername" => "admin",
		"sabnzbdPassword" => "admin",
		"uTorrentUsername" => "admin",
		"uTorrentPassword" => "admin",
		"transmissionUsername" => false,
		"transmissionPassword" => false,

		# Sickbeard - Missed or Coming?
		# Australia, for example, is almost an entire day ahead of America so American TV shows 
		# air the day after they say they're going to air, so instead of "coming shows", we use "missed shows"
		# to indicate what's coming out today. 
		# Set to true for "missed", false for "coming"
		"sickMissed" => true,

		# Show popups when hovering over coming shows?
		"sickPopups" => true,

		# Debug
		"debug" => false,

		# Show trailers button
		"showTrailers" => true,

		# Wifi
		# WifiName is also used for page title
		"showWifi" => true,
		"wifiName" => "Home Network",
		"wifiPassword" => "abcd1234",

		# Bookmarks
		"bookmarks" => array(
			0 => array(
				"label" => "Season Start Dates",
				"url" => "intranet/comingseasons.php",
				"icon" => "intranet/images/tv.png",
			),
                        1 => array(
                                "label" => "NZB.su",
                                "url" => "https://nzb.su/login",
                                "icon" => "intranet/images/nzb_su.png",
                        ),
                        2 => array(
                                "label" => "Monit",
                                "url" => "/monit/",
                                "icon" => "",
                        ),
                        3 => array(
                                "label" => "Owncloud",
                                "url" => "/owncloud",
                                "icon" => "intranet/images/owncloud.png",
                        ),
                        4 => array(
                                "label" => "SpotWeb",
                                "url" => "/spotweb/",
                                "icon" => "",
                        ),
                        5 => array(
                                "label" => "Lazy Librarian",
                                "url" => "/lazylibrarian/",
                                "icon" => "",
                        ),
                        6 => array(
                                "label" => "Mylar",
                                "url" => "/mylar/",
                                "icon" => "",
                        ),
                        7 => array(
                                "label" => "Ampache",
                                "url" => "/ampache/",
                                "icon" => "",
                        ),
			8 => array(
				"label" => "Trakt.TV",
				"url" => "http://trakt.tv",
				"icon" => "",
			),

		),

	);
?>
