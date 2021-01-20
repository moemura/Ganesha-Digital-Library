<?php

////////////////////////////////////////////////////////////////////////////////////////////////////////////
// CLASS NAME:  OCR_CAPTCHA                                                                               //
// FILE NAME :  CLASS_SESSION.INC.PHP                                                                     //
// LANGUAGE  :  PHP                                                                                       //
// AUTHOR    :  Julien PACHET                                                                             //
// EMAIL     :  j|u|l|i|e|n| [@] |p|a|c|h|e|t.c|o|m                                                       //
// VERSION   :  1.0                                                                                       //
// CREATION  :  17/03/2004                                                                                //
// LICENCE   :  GNU GPL                                                                                   //
////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////
// What the class does:                                                                                   //
////////////////////////////////////////////////////////////////////////////////////////////////////////////
// * Make a catcha picture (Completely Automated Public Turing to tell Computers from Humans Apart)       //
//   To test if a human is really behind the web page. In a form, you put a captcha picture, and a text   //
//   Field, and then...                                                                                   //
// * Check if the text typed in the field from the picture (private key) corrrespond to the public_key    //
//   that the class inserted in a hidden field                                                            //
// Indeed, the class can prevent from automatic (bot) filling form for example:                           //
//   _ poll                                                                                               //
//   _ account creation                                                                                   //
//   _ account loggin (prevent from brute force password tries                                            //
//   _ check for access to a given page (to stop bot like search bot or spam bot                          //
//   _ ...                                                                                                //
// More infos at http://www.captcha.net                                                                   //
////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Changelog:                                                                                             //
// ----------                                                                                             //
//  Date        Version   Actions                                                                         //
// ------------------------------------------------------------------------------------------------------ //
//  16/03/2004  0.90      Class creation and test                                                         //
//  17/03/2004  1.0       Final and tested version                                                        //
//  22/03/2004  1.1       picture can now be either jpg or png type (default png)                         //
//  13/04/2004  1.2       picture file is now placed in tmp directory in the webserver, neither in system //
////////////////////////////////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////////
// Need to work:                                                                                          //
////////////////////////////////////////////////////////////////////////////////////////////////////////////
// other files: none                                                                                      //
// other datas: a private string (see in file class) use to make private key from public key              //
////////////////////////////////////////////////////////////////////////////////////////////////////////////

  class ocr_captcha {
    var $key;       // ultra private static text
    var $long;      // size of text
    var $lx;        // width of picture
    var $ly;        // height of picture
    var $nb_noise;  // nb of background noisy characters
    var $filename;  // file of captcha picture stored on disk
    var $imagetype="png"; // can also be "png";
    var $lang="en"; // also "en"
    var $public_key;    // public key
    var $font_file="/../module/member/lucon.ttf";
    function __construct($long=5,$lx=120,$ly=30,$nb_noise=25) {
      $this->key=md5("A nicely little text to stay private and use for generate private key");
      $this->long=$long;
      $this->lx=$lx;
      $this->ly=$ly;
      $this->nb_noise=$nb_noise;
      $this->public_key=substr(md5(uniqid(rand(),true)),0,$this->long); // generate public key with entropy
	  $this->font_file = dirname(__FILE__) . $this->font_file;
    }
    
    function get_filename($public="") { 
      if ($public=="")
        $public=$this->public_key;
      if (strpos($_SERVER['SystemRoot'], ":\\")===false) // so linux system
        $rad="files/misc/"; // Document_root works nicely here  
      else // windows system 
        $rad="files/misc/"; 
      return $rad."captcha.".$this->imagetype;
    }
    
    // generate the private text coming from the public text, using $this->key (not to be public!!), all you have to do is here to change the algorithm
    function generate_private($public="") {
      if ($public=="")
        $public=$this->public_key;
      return substr(md5($this->key.$public),16-$this->long/2,$this->long);
    }
    
    // check if the public text is link to the private text
    function check_captcha($public,$private) {
      // when check, destroy picture on disk
      if (file_exists($this->get_filename($public)))
        unlink($this->get_filename($public));
      return (strtolower($private)==strtolower($this->generate_private($public)));
    }
    
    // display a captcha picture with private text and return the public text
    function make_captcha($noise=true) {
      $private_key = $this->generate_private();
      $image = imagecreatetruecolor($this->lx,$this->ly);
      $back=ImageColorAllocate($image,intval(rand(224,255)),intval(rand(224,255)),intval(rand(224,255)));
      ImageFilledRectangle($image,0,0,$this->lx,$this->ly,$back);
      if ($noise) { // rand characters in background with random position, angle, color
        for ($i=0;$i<$this->nb_noise;$i++) {
          $size=intval(rand(6,14));
          $angle=intval(rand(0,360));
          $x=intval(rand(10,$this->lx-10));
          $y=intval(rand(0,$this->ly-5));
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          $text=chr(intval(rand(45,250)));
          ImageTTFText ($image,$size,$angle,$x,$y,$color,$this->font_file,$text);
        }
      }
      else { // random grid color
        for ($i=0;$i<$this->lx;$i+=10) {
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          imageline($image,$i,0,$i,$this->ly,$color);
        }
        for ($i=0;$i<$this->ly;$i+=10) {
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          imageline($image,0,$i,$this->lx,$i,$color);
        }
      }
      // private text to read
      for ($i=0,$x=5; $i<$this->long;$i++) {
        $r=intval(rand(0,128));
        $g=intval(rand(0,128));
        $b=intval(rand(0,128));
        $color = ImageColorAllocate($image, $r,$g,$b);
        $shadow= ImageColorAllocate($image, $r+128, $g+128, $b+128);
        $size=intval(rand(12,17));
        $angle=intval(rand(-30,30));
        $text=strtoupper(substr($private_key,$i,1));
        ImageTTFText($image,$size,$angle,$x+2,26,$shadow,$this->font_file,$text);
        ImageTTFText($image,$size,$angle,$x,24,$color,$this->font_file,$text);
        $x+=$size+2;
      }
      if ($this->imagetype=="jpg")
        imagejpeg($image, "files/misc/captcha.jpg", 100);
      else
        imagepng($image, "files/misc/captcha.png");
      ImageDestroy($image);
    }
	
	function get_public_key() {
		return $this->public_key;
	}
    
    function display_captcha($noise=true) {
      $this->make_captcha($noise);
      $res="<img src='".$this->get_filename()."' border='0'>\n";
      return $res;
    }
  }
  
?>
