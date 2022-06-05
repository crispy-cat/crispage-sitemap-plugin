<?php
	// Copy files
	$app->installerMessage("Copying files");
	FileHelper::copyRecurs(PACKAGE_DIR . "/plugins", \Config::APPROOT . "/plugins");
	
	// Register extensions
	installer_message("Registering extensions");
	\Crispage\Helpers\ExtensionHelper::registerExtension("crispycat/SitemapPlugin", "plugin", "backend");
	
	$app->installerMessage("Sitemap plugin installed!", \Crispage\Application\InstallerApplication::IMSG_INFO);
?>
