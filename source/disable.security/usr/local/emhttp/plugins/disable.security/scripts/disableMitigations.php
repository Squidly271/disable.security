#!/usr/bin/php
<?PHP
function startsWith($haystack, $needle) {
	if ( !is_string($haystack) || ! is_string($needle) ) return false;
	return $needle === "" || strripos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

$syslinux = file("/boot/syslinux/syslinux.cfg",FILE_IGNORE_NEW_LINES);
$newsyslinux = $syslinux;
foreach ($syslinux as $index => $line) {
	if ( startsWith(trim($line),"menu") && strpos($line,"default") ) {
		for ( $i = $index; $i < count($syslinux); $i++ ) {
			if ( startsWith(trim($syslinux[$i]),"append") && ! $found) {
				$found = true;
				$newsyslinux[$i] = rtrim($syslinux[$i])." pti=off spectre_v2=off l1tf=off mds=off nospec_store_bypass_disable no_stf_barrier";
				break;
			}
		}
	}
}

file_put_contents("/boot/syslinux/syslinux.cfg",implode("\n",$newsyslinux));
file_put_contents("/tmp/disable.mitigations.reset","blah");
echo "disabled";
?>