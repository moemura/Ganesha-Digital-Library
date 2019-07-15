<?php
if (preg_match("/stdout.php/i",$_SERVER['PHP_SELF'])) {
    die();
}


/**
	Kelas yang menangani keluaran standar
*/

class stdout{
		
		/**
			Konstruktor
		*/
		function stdout(){}
		
		/**
			Header HTML dengan redirection
			
			Parameter
				$second 	:
				$uri		:
			Return
				$html		:
		*/
		function header_redirect($second,$uri){
			$html = "<META HTTP-EQUIV=Refresh CONTENT=\"$second; URL=$uri\">
					<title>Redirection</title>";
			return $html;
		}
		
		/**
			Print message
			
			Parameter
				$header 	:
				$message	:
			Return
				$html		:
		*/
	function print_message($header, $message){
		return "<div class=\"block\"><a name=\"responseAction\"></a><h4 class=\"title\">$header</h4>$message</div>";
	}
	
	/**
		Menampilkan response yang diterima. Response yang ditampilkan
		memiliki format XML.
				
		Parameter
			$response 	:
		Return
			$html		:
	*/
	function show_response($response)
	{
		global $gdl_sync;
		
		if ($gdl_sync['sync_show_response'] == 0){ 
			$html = "<h4 class=\"title\"><a name=\"responseAction\"></a>Show Response Format</h4>";
			$response = wordwrap($response,90);
			$html .= "<pre>".htmlspecialchars($response)."</pre>";
		}
		return "<div class=\"block\">$html</div>";
	}
	
}
?>