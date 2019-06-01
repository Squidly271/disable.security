#!/usr/bin/php
<?PHP
function startsWith($haystack, $needle) {
	if ( !is_string($haystack) || ! is_string($needle) ) return false;
	return $needle === "" || strripos($haystack, $needle, -strlen($haystack)) !== FALSE;
}

$syslinux = file("/boot/syslinux/syslinux.cfg",FILE_IGNORE_NEW_LINES);
foreach ($syslinux as $line) {
	if ( startsWith(trim($line),"append")) {
		$line = rtrim($line)." pti=off spectre_v2=off l1tf=off nospec_store_bypass_disable no_stf_barrier";
	}
	$newsyslinux .= "$line\n";
}
file_put_contents("/boot/syslinux/syslinux.cfg",$newsyslinux);
file_put_contents("/tmp/disable.mitigations.reset","blah");
echo "hi";
?>