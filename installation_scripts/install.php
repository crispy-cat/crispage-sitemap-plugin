<?php
	// Copy files
	installer_message("Copying files");
	FileHelper::copyRecurs(TMPEXT . "/plugins", Config::APPROOT . "/plugins");
	
	// Register extensions
	installer_message("Registering extensions");
	ExtensionHelper::registerExtension("crispycat/SitemapPlugin", "plugin", "backend");
	
	installer_message("Sitemap plugin installed!", IMSG_INFO);
?>
