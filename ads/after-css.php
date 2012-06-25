<?php
$obt_ads["background"] = "ffffff";
$obt_ads["text"] = "000000";
$obt_ads["link"] = obt_get_hsl2rgb($linkhue,$linksaturation,80);
$obt_ads["border"] = "dcdcdc";
?>
.banner {
	margin: 15px 0px -3px 20px;
	overflow: hidden;
	}
	body.lt1024 .banner {
		margin-left: 0px;
		}
.leaderboard {
	margin: 20px 0px -20px;
	}
	.leaderboard iframe {
		margin-left: -2px;
		}
@media print {
	.banner, .leaderboard {
		display: none;
		}
	}
