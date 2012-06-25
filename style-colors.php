<?php
FUNCTION obt_get_hsl2rgb($hue,$saturation,$luminance){
	IF ($saturation == 0) $red = $green = $blue = $luminance;
	ELSE{
		$hue = round($hue/255,2);
		$saturation = round($saturation/255,2);
		$luminance = round($luminance/255,2);

		IF ($luminance < 0.5) $v2 = $luminance*(1+$saturation);
		ELSE $v2 = ($luminance+$saturation)-($saturation*$luminance);

		$v1 = 2*$luminance-$v2;
		$red = 255*obt_hue2rgb($v1,$v2,$hue+(1/3));
		$green = 255*obt_hue2rgb($v1,$v2,$hue);
		$blue = 255*obt_hue2rgb($v1,$v2,$hue-(1/3));
	};
	$red = sprintf("%02x",round($red));
	$green = sprintf("%02x",round($green));
	$blue = sprintf("%02x",round($blue));
	$rgb = "{$red}{$green}{$blue}";
	RETURN $rgb;
};
FUNCTION obt_hsl2rgb($hue,$saturation,$luminance){
	echo obt_get_hsl2rgb($hue,$saturation,$luminance);
};
FUNCTION obt_hue2rgb($v1,$v2,$hue){
	IF ($hue < 0) $hue += 1;
	IF ($hue > 1) $hue -= 1;
	IF ((6*$hue) < 1) RETURN ($v1+($v2-$v1)*6*$hue);
	IF ((2*$hue) < 1) RETURN ($v2);
	IF ((3*$hue) < 2) RETURN ($v1+($v2-$v1)*((2/3)-$hue)*6);
	RETURN ($v1);
};
?>