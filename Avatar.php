<?php
include_once("Global.php");

$ID = mysqli_real_escape_string($connection, preg_replace('/\D/', '', $_GET['ID'] ?? ''));
$Username = mysqli_real_escape_string($connection, $_GET['Username'] ?? '');

if (!$Username) {
	$query = "SELECT * FROM Users WHERE ID=?";
	$statement = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($statement, "s", $ID);
} else {
	$query = "SELECT * FROM Users WHERE Username=?";
	$statement = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($statement, "s", $Username);
}

mysqli_stmt_execute($statement);
$result = mysqli_stmt_get_result($statement);
$gU = mysqli_fetch_object($result);

// stuff

$Body = !empty($gU->Body) ? $gU->Body : "thingTransparent.png";
$Background = !empty($gU->Background) ? $gU->Background : "thingTransparent.png";
$Eyes = !empty($gU->Eyes) ? $gU->Eyes : "thingTransparent.png";
$Mouth = !empty($gU->Mouth) ? $gU->Mouth : "thingTransparent.png";
$Hair = !empty($gU->Hair) ? $gU->Hair : "thingTransparent.png";
$Bottom = !empty($gU->Bottom) ? $gU->Bottom : "thingTransparent.png";
$Top = !empty($gU->Top) ? $gU->Top : "thingTransparent.png";
$Hat = !empty($gU->Hat) ? $gU->Hat : "thingTransparent.png";
$Shoes = !empty($gU->Shoes) ? $gU->Shoes : "thingTransparent.png";
$Accessory = !empty($gU->Accessory) ? $gU->Accessory : "thingTransparent.png";

class StackImage
{
	private $image;
	private $width;
	private $height;

	public function __construct($Path)
	{
		if (!isset($Path) || !file_exists($Path)) {
			return;
		}
		$this->image = imagecreatefrompng($Path);
		imagesavealpha($this->image, true);
		imagealphablending($this->image, true);
		$this->width = imagesx($this->image);
		$this->height = imagesy($this->image);
	}

	public function AddLayer($Path)
	{
		if (!isset($Path) || !file_exists($Path)) {
			return;
		}
		$new = imagecreatefrompng($Path);
		if ($new === false) {
			die("Unable to create image from: $Path");
		}
		imagesavealpha($new, true);
		imagealphablending($new, true);
		
		if ($this->image !== null) {
			$dst_width = imagesx($this->image);
			$dst_height = imagesy($this->image);
			$src_width = imagesx($new);
			$src_height = imagesy($new);
			imagecopy($this->image, $new, 0, 0, 0, 0, $src_width, $src_height);
		} else {
			$this->image = $new;
			$this->width = imagesx($new);
			$this->height = imagesy($new);
		}
		
		imagedestroy($new);
	}



	public function Output($type = "image/png")
	{
		header("Content-Type: {$type}");
		imagepng($this->image);
		imagedestroy($this->image);
	}

	public function GetWidth()
	{
		return $this->width;
	}

	public function GetHeight()
	{
		return $this->height;
	}
}

$Image = new StackImage("Images/thingTransparent.png");
$Image->AddLayer("Store/Dir/".$Background."");
$Image->AddLayer("Store/Dir/".$Body."");
$Image->AddLayer("Store/Dir/".$Eyes."");
$Image->AddLayer("Store/Dir/".$Mouth."");
$Image->AddLayer("Store/Dir/".$Bottom."");
$Image->AddLayer("Store/Dir/".$Top."");
$Image->AddLayer("Store/Dir/".$Hair."");
$Image->AddLayer("Store/Dir/".$Hat."");
$Image->AddLayer("Store/Dir/".$Shoes."");
$Image->AddLayer("Store/Dir/".$Accessory."");
if (date("Y-m-d H:i:s") < $gU->expireTime) {
	$Image->AddLayer("Images/Online.png");
}
if ($gU->Premium == 1) {
	$Image->AddLayer("Images/pw.png");
}

$Image->Output();
?>