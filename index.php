<?php include('intranet/serverconfig.php'); ?>
<?php include('intranet/lib/functions.php'); ?>
<?php if($config['uTorrent']) {include('intranet/lib/utorrent_php_api.php');} ?>
<?php if($config['transmission']) {include('intranet/lib/transmissionrpc.class.php');} ?>
<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta charset="UTF-8">
		<title><?= $config['wifiName']; ?> Intranet</title>
		<link rel="stylesheet" href="intranet/style.css" />
		<link rel="shortcut icon" href="favicon.ico" />
	</head>
	<body>
		<h1><?= $config['wifiName']; ?> Server</h1>

		<?php ## Check if everything is disabled
			if (!$config['sickbeard'] && !$config['couchpotato'] && !$config['headphones'] && !$config['sabnzbd'] && !$config['showWifi'] && !$config['showTrailers']) :

				echo "<img src='intranet/images/mymanjackie.png' alt='error' />";

			else :
		?>
		
		<?php if( $config['sickbeard'] ) : 
			  if ( $config['sickMissed'] ) : $sbType = "missed"; else: $sbType = "today"; endif;
		?>
		<div class="sickbeardShows">
			<h3>TV Today</h3>
			<?php

				$sbJSONURL = $sickbeardURL."/api/".$config['sickbeardAPI']."/?cmd=future&sort=date&type=".$sbType;

				if($config['debug']) {echo "TV Today URL: ".$sbJSONURL;}

				$sbJSON = file_get_contents($sbJSONURL);
				$sbShows = json_decode($sbJSON);

				echo "<ul class='comingShows'>";

				# List shows
				if(empty($sbShows)){
					# quick check if there are any shows today.
					echo "<li>No shows today.</li>";
				} else {
					# Run through each show
					foreach($sbShows->{'data'}->{$sbType} as $episode) {
						echo "<li>";

						# Sickbeard Popups
						if($config ['sickPopups']) :
						echo "<span class='showPopup'>";
						echo "<img src='".$sickbeardURL."/showPoster/?show=".$episode->{'tvdbid'}."&amp;which=poster' class='showposter' alt='".$episode->{'tvdbid'}."'/>";
						echo "</span>";
						endif;

						# Show name and number
						echo "<strong class='showname'>".$episode->{'show_name'}."</strong><br />";
						echo "<span class='showep'>".$episode->{'season'}."x".$episode->{'episode'}." - ". $episode->{'ep_name'}."</span>";
						echo "</li>";
					} 
				}
				echo "</ul>";

				$sbJSONdoneURL = $sickbeardURL."/api/".$config['sickbeardAPI']."/?cmd=history&limit=50";
				$sbJSONdone = file_get_contents($sbJSONdoneURL);
				$sbShowsdone = json_decode($sbJSONdone);
				$todaysDate = date('Y-m-d');

				if($config['debug']){echo "TV Complete Today URL: ".$sbJSONdoneURL;}

				echo "<ul class='snatchedShows'>";

				# List shows
				# Run through each show
				foreach($sbShowsdone->{'data'} as $episode) {

					if (substr($episode->date,0,10) == $todaysDate && $episode->status == "Snatched") :

						// Check Quality Snatched
						if ($episode->{'quality'} == "SD TV") :
							$quality = "sd";
						elseif ($episode->{'quality'} == "HD TV") :
							$quality = "hd";
						endif;

						echo "<li class=".$quality.">";

						# Sickbeard Popups
						if($config ['sickPopups']) :
						echo "<span class='showPopup'>";
						echo "<img src='".$sickbeardURL."/showPoster/?show=".$episode->{'tvdbid'}."&amp;which=poster' class='showposter' alt='".$episode->{'tvdbid'}."' />";
						echo "</span>";
						endif;

						# Show name and number
						echo "<strong class='showname'>".$episode->{'show_name'}." <small>".$episode->{'season'}."x".$episode->{'episode'}."</small></strong>";
						echo "</li>";

					endif;

				} 
				echo "</ul>";

			?>
		</div>
		<?php endif; ?>

		<?php ## Action Buttons ?>
		<?php if( $config['sickbeard'] ) : ?>
		<a href="<?= $sickbeardURL; ?>" title="SickBeard" class="actionButton big sickbeard"><span>SickBeard</span></a>
		<?php endif; ?>
		<?php if( $config['couchpotato'] ) : ?>
		<a href="<?= $couchpotatoURL; ?>" title="CouchPoato" class="actionButton big couchpotato"><span>CouchPotato</span></a>
		<?php endif; ?>
		<?php if( $config['headphones'] ) : ?>
		<a href="<?= $headphonesURL; ?>" title="Headphones" class="actionButton big headphones"><span>Headphones</span></a>
		<?php endif; ?>

		<?php ## SABnzbd ?>
		<?php if( $config['sabnzbd'] ) : ?>
		<a href="<?= $sabURL; ?>" title="SABnzbd" class="actionButton big sabnzb"><span>SABnzbd</span></a>

		<div class="downloadFrame clearfix"><div class="downloadFrameSlide clearfix">
			<div class="downloadPage downloadPageCurrent">
				<h2>Currently Downloading</h2>
				<a href="#" class="go actionButton small">&gt;</a>
				<?php

					$sabStatusXML = $sabURL."/api?mode=qstatus&output=xml&apikey=".$config['sabnzbdAPI'];
					if($config['debug']){echo "SABnzbd Status URL: ".$sabStatusXML;}
					$temp = file_get_contents($sabStatusXML);
					$data = simplexml_load_string($temp);
					for($i = 0, $j = count($data->jobs[0]->job); $i < $j ; $i++) {
					$filename = $data->jobs[0]->job[$i]->filename;
					$mbFull = $data->jobs[0]->job[$i]->mb;
					$mbLeft = $data->jobs[0]->job[$i]->mbleft;
					$mbDone = $mbFull - $mbLeft;
					}
					if(isset($filename)) {

						$mbFullNoRound = explode(".",$mbFull);
						$mbPercent = $mbDone / $mbFullNoRound[0] * 100;
						$mbPercentPretty = explode(".",$mbPercent);

						echo "<span class='currentdl'>";
						if ($data->paused == "True") {echo "PAUSED: ";}
						echo $filename."</span>";
						echo "<progress value='".$mbDone."' max='".$mbFull."'></progress>";
						echo "<span class='stats'>".$mbDone."mb / ".$mbFullNoRound[0]."mb (".$mbPercentPretty[0]."%) @ ". $data->speed ."</span>";

					} else {
						
						echo "<em class='currentdl'>No current downloads</em>";

					}
				?>

			</div>
			<div class="downloadPage downloadPageHistory">
				<h2>Recently Finished</h2>
				<a href="#" class="go actionButton small">&lt;</a>
				<?php
					$temp = file_get_contents($sabURL."/api?mode=history&start=0&limit=5&output=xml&apikey=".$config['sabnzbdAPI']);	
					$data = simplexml_load_string($temp);
					if (isset($data)) {
					echo "<ul>";
					foreach($data->slots[0] as $slot) {
						echo "<li>".$slot->category." - ".$slot->nzb_name."</li>";
					}
					echo "</ul>";
					}
				?>
			</div>
			<?php endif; ?>
		</div></div>

		<?php ## uTorrent Web GUI ?>
		<?php if( $config['uTorrent'] ) : ?>
		<section class="clearfix">
			<a href="http://<?= $config['uTorrentURL']; ?>:<?= $config['uTorrentPort']; ?>/gui/" title="uTorrent" class="actionButton big utorrent"><span>uTorrent</span></a>

			<div class="downloadFrame">
				<div class="downloadPage downloadPageCurrent">
					<h2>Currently Downloading</h2>
					<?php

				        $utorrent = new uTorrentAPI($config);

				        // Create some variables
				        $torrentAPI = $utorrent->get_torrent_list();
				        $torrents = $torrentAPI['torrents'];
				        $torrentsComplete = array();
				        $torrentsDownloading = array();

				        // Check if any results are returned
				        if(sizeof($torrents)==0) {

				        	echo "<em>No current downloads</em>";

				        } else {

					        // Run through each torrent and insert in to appropriate variables
					        foreach($torrents as $torrent) {
					            if($torrent[4] == "1000") {
					                array_push($torrentsComplete, $torrent);
					            } else {
					                array_push($torrentsDownloading, $torrent);
					            }
					        }

					        // Cut off array at 5 each
					        $torrentsDownloading = array_slice($torrentsDownloading,0,5);

					        // List all pending downloads
					        foreach($torrentsDownloading as $torrentDone) {
					            $name = $torrentDone[2];
					            $sizeFull = $torrentDone[3];
					            $sizeDone = $torrentDone[5];
					            $percentage = $torrentDone[4]/10;
					            $speed = $torrentDone[9];

					            echo "<div class='torrent'>";
					            echo $name;
					            echo "<progress value='".$sizeDone."' max='".$sizeFull."'></progress>";
					            echo "<span class='stats'>";
					            echo ByteSize($sizeDone)." / ".ByteSize($sizeFull)." (".$percentage."%)";
					            echo " @ " .ByteSize($speed);
					            echo "</span>";
					            echo "</div>";
					        }

					    }

					?>
				</div>
			</div>
		</section>
		<?php endif; ?>

		<?php if($config['transmission']) : ?>	
		<section class="clearfix">
			<a href="<?= $transmissionURL; ?>" title="Transmission" class="actionButton big transmission"><span>Transmission</span></a>

			<div class="downloadFrame clearfix">
			<div class="downloadFrameSlide clearfix">
				<div class="downloadPage downloadPageCurrent">
				<a href="#" class="go actionButton small">&gt;</a>
					<h2>Currently Downloading</h2>
					<?php
						//$transmissionAPI = new TransmissionRPC($transmissionURL."/transmission/rpc", null, null, true);

$rpc = new TransmissionRPC();
$rpc->url = $transmissionURL."/rpc";
try
{
  $rpc->return_as_array = true;
  $result = $rpc->get();
                                        $torrentsComplete = array();
                                        $torrentsDownloading = array();

                                        // Check if any results are returned
                                        if(sizeof($result['arguments']['torrents'])==0) {

                                                echo "<em>No current downloads</em>";

                                        } else {

                                                // Run through each torrent and insert in to appropriate variables
                                                foreach($result['arguments']['torrents']  as $torrent) {
                                                    if($torrent['isFinished']) {
                                                       array_push($torrentsComplete, $torrent);
                                                    } else {
                                                        array_push($torrentsDownloading, $torrent);
                                                    }
                                                }

                                                // Cut off array at 5 each
                                                $torrentsDownloading = array_slice($torrentsDownloading,0,5);

                                              // List all pending downloads
                                                foreach($torrentsDownloading as $torrentDone) {
                                                    $name = $torrentDone['name'];
                                                    $sizeFull = $torrentDone['totalSize'];
						    if ($sizeFull==0) {
						    	$sizeFull = 1;
						    }
                                                    $sizeDone = $torrentDone['pieceSize']*100;
                                                    $percentage = $torrentDone['percentDone']*100;

                                                    $speed = $torrentDone['rateDownload'];

                                                    echo "<div class='torrent'>";
                                                    echo $name;
                                                    echo "<progress value='".$sizeDone."' max='".$sizeFull."'></progress>";
                                                    echo "<span class='stats'>";
                                                    echo ByteSize($sizeDone)." / ".ByteSize($sizeFull)." (".$percentage."%)";

                                                    echo " @ " .ByteSize($speed);
                                                    echo "</span>";
                                                    echo "</div>";
                                                }
						echo <<< END
						                        </div>
								                        <div class="downloadPage downloadPageHistory">
						                                <h2>Recently Finished</h2>
				<a href="#" class="go actionButton small">&lt;</a>
END;
                                        echo "<ul>";
					foreach($torrentsComplete as $slot) {
		                           echo "<li>".$slot['name']."</li>";
                                        }	
					echo "</ul>";


                                        }



  $rpc->return_as_array = false;
} catch (Exception $e) {
print "<pre>".$e->getMessage()."</pre>";
  die('[ERROR] ' . $e->getMessage() . PHP_EOL);
}

					?>
				</div>
			</div>
			</div>
		</section>
		<?php endif; ?>

		<?php ## Wifi ?>
		<?php if( $config['showWifi'] ) : ?>
			<div class="wifi">
				<h2>Wifi Password for <?= $config['wifiName'] ?></h2>
				<h3><?= $config['wifiPassword']; ?></h3>
			</div>
		<?php endif; ?>

		<div class="secondaryButtons clearfix">

			<?php if( $config['showTrailers'] ) : ?>
			<a href="http://www.hd-trailers.net/" target="_blank" class="actionButton small icon iconTrailer"><span>Watch Trailers</span></a>
			<?php endif; ?>

			<?php if ( !empty($config['bookmarks']) ) {
				foreach ($config['bookmarks'] as $bookmark) {

					// Check for custom icon, otherwise use favicon from website
					if($bookmark['icon']) {
						$icon = $bookmark['icon'];
					} else {
						$icon = $bookmark['url']."/favicon.ico";
					}
					
					echo "<a href='".$bookmark['url']."' target='_blank' class='actionButton small icon'><span style='background-image: url(".$icon.");'>".$bookmark['label']."</span></a>";

				}
			}
			?>

		</div>

		<?php ## Ending check for all-disabled ?>
		<?php endif; ?>

		<script src="intranet/js/jquery.js"></script>
		<script src="intranet/js/scripts.js"></script>
	</body>
</html>
