<?php echo '<?xml version="1.0" encoding="ISO-8859-1" ?>' . "\n";

// Path to local git binnary
$kgGitBinPath = '/usr/bin/git';

/* get the config */
require_once( realpath( dirname( __FILE__ ) )  . '/doc-config.php' );

/* load kalturaCache helper */
require_once( dirname( __FILE__ ) . '/../modules/KalturaSupport/KalturaCache.php');
$cache = new KalturaCache( 'file_cache_adapter' );
/* check the cache */

/* poulate the cache */ 
echo generate_docs_rss();

function generate_docs_rss(){
	global $wgGitRepoPath ;
	$baseUrl = 'http://' . $_SERVER['HTTP_HOST'] . str_replace('rss.php', '', $_SERVER['REQUEST_URI']);
	ob_start();
?>
<rss version="2.0">
<channel>
	<title>Kaltura Feature Hub</title>
	<link><?php echo $baseUrl ?></link>
	<description>Demos, Configuration Options and Tools for Integrating the Kaltura Player</description>
<?php 
$featureSet = include( 'featureList.php' );
foreach( $featureSet as $featureCategoryKey => $featureCategory ){
	foreach( $featureCategory['featureSets'] as $featureSetKey => $featureSet){
		foreach( $featureSet['testfiles'] as $testfileKey =>  $testfile ){
			$filePath = realpath( dirname( __FILE__ ) . '/../modules/' . $testfile['path'] );
			if( $wgGitRepoPath ){
				$dateHR = trim( execGit( 'log -1 --format="%ad" -- ' . $filePath ) );
			} else {
				$dateHR = date( DATE_RFC822, filemtime($filePath) );
			}
			?>
			<item>
				<title><?php echo $testfile['title'] ?></title>
				<link><?php echo $baseUrl . $featureCategoryKey .
					 '/' . $featureSetKey . '/' . $testfileKey ?></link>
				<description><?php 
				// for now just [re]output the title: 
				echo $testfile['title'];
				?></description>
				<category domain="<?php echo $baseUrl . $featureCategoryKey?>"><?php echo $featureCategory['title'] ?></category>
				<pubDate><?php echo $dateHR ?></pubDate>
				<content>
				</content>
			</item><?php 
		}
	}
}
?>
</channel>
</rss>
<?php 
	return ob_get_clean();
}

function execGit( $args ){
	global $wgGitRepoPath, $kgGitBinPath;

	// Make sure we are "in the repo" dir:
	if( is_dir( $wgGitRepoPath ) ){
		chdir( $wgGitRepoPath );
	}
	$gitOutput = shell_exec( $kgGitBinPath . ' ' . $args );
	return $gitOutput;
}
