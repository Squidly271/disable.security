#!/usr/bin/php
<?PHP
function startsWith($haystack, $needle) {
	if ( !is_string($haystack) || ! is_string($needle) ) return false;
	return $needle === "" || strripos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

$kernelVersion = exec("uname -r");

$syslinux = file("/boot/syslinux/syslinux.cfg",FILE_IGNORE_NEW_LINES);
$newsyslinux = $syslinux;
$found = false;
foreach ($syslinux as $index => $line) {
	if ( startsWith(trim($line),"menu") && strpos($line,"default") ) {
		for ( $i = $index; $i < count($syslinux); $i++ ) {
			if ( startsWith(trim($syslinux[$i]),"append") && ! $found) {
				$found = true;
				$string = version_compare("5.1",$kernelVersion,">") ? " pti=off spectre_v2=off l1tf=off mds=off nospec_store_bypass_disable no_stf_barrier" : " mitigations=off";
				$newsyslinux[$i] = rtrim($syslinux[$i]).$string;
				break;
			}
		}
	}
}

file_put_contents("/boot/syslinux/syslinux.cfg",implode("\n",$newsyslinux));
file_put_contents("/tmp/disable.mitigations.reset","disabled");
echo "disabled";
?>