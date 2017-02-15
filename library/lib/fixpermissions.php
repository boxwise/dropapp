<?php

	function permissionsFixer() {
		$perms = '0777';
		$aDirs = getDirsRecursive('content');

		foreach ($aDirs AS $dir) {
			fixPermissions($dir,$perms);
		}
	}

	function fixPermissions($file,$code) {
		global $settings;

		$perm = substr(sprintf('%o', fileperms($_SERVER['DOCUMENT_ROOT'].$file)), -4);
		if($perm!=$code) {
			if($_SERVER['Local']) {
				$fh = ftp_connect('zinnebeeldtest.nl');
				$site = substr($_SERVER['DOCUMENT_ROOT'], (strrpos($_SERVER['DOCUMENT_ROOT'], '/')));
				$login_result = ftp_login($fh, 'zinnebeeld', 'xs4zb01!');
			} else {
				$fh = ftp_connect($settings['ftp_host']);
				$site = $settings['ftp_site'];
				$login_result = ftp_login($fh, $settings['ftp_user'], $settings['ftp_pass']);
			}
			$command = 'CHMOD '.$code.' '.$site.$file;
			ftp_site($fh, $command);
			ftp_close($fh);
		}
	}
