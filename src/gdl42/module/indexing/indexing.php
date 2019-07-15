<?php
if (preg_match("/indexing.php/i",$_SERVER['PHP_SELF'])) {
    die();
}

include("./class/indexing.php");
$indexing = new indexing();
$main = date("Y-m-d H:i:s")."<br/><br/>$main";
$index_by_database = $_SESSION['index_by_database'];

if (empty($index_by_database)) {
	// Indexing using System (swish-e).
	$dump = $indexing->dump();
	$main .= $indexing->dump_count." metadata "._HASBEENDUMP."\n";
	$main .= $indexing->build();
} else {
	// Indexing using database.
	$delay		= 1; // delay to redirect.
	$page_size  = 1000; // num record to be indexed.
	$response = $indexing->build_indexing_record($delay, $page_size);
	if ($response['redirect'] == 1) {
		$main .= $response['url'];
		$main .= "Perform indexing for ". count($response['identifier'])." identifier(s)";
		$main .= "<br/>Please wait to perform indexing other identifier(s)...........";
	} else {
		$main .= "Indexing all identifiers has been accomplished.";
	}
}
$gdl_content->set_main(gdl_content_box($main,"Indexing Metadata"));

?>
