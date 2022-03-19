<?php
	// Copy files
	installer_message("Copying files");
	FileHelper::copyRecurs(TMPEXT . "/plugins", Config::APPROOT . "/plugins");
	
	// Register extensions
	installer_message("Registering extensions");
	$app->extensions->registerExtension("crispycat/SitemapPlugin", "plugin", "backend");
	
	// Register plugins
	$app->plugins->setPlugin("crispycat.SitemapPlugin", new Plugin(array(
		"id" => "crispycat.SitemapPlugin",
		"class" => "crispycat/SitemapPlugin",
		"priority" => 0,
		"scope" => "backend",
		"created" => time(),
		"modified" => time(),
		"options" => array(
			"sitemap_file" => "sitemap.xml",
			"changefreq" => "daily",
			"priority" => "0.8",
			"index_priority" => "1",
			"generate_robots" => 1
		)
	)));
	
	installer_message("Sitemap plugin installed!", IMSG_INFO);
?>
