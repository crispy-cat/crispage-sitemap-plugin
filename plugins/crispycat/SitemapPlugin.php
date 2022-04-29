<?php
	defined("CRISPAGE") or die("Application must be started from index.php!");

	class SitemapPlugin extends Plugin {
		public function execute() {
			global $app;

			$app->vars["sitemapplugin"] = $this;


			$app->events->registerAction(new EventAction(array(
				"id" => "crispycat.SitemapPlugin.generate_on_article_edit",
				"event" => "assets.articles.set",
				"priority" => 0,
				"action" => function($app) {
					$app->vars["sitemapplugin"]->generateSitemap();
				}
			)));

			$app->events->registerAction(new EventAction(array(
				"id" => "crispycat.SitemapPlugin.generate_on_article_delete",
				"event" => "assets.articles.delete",
				"priority" => 0,
				"action" => function($app) {
					$app->vars["sitemapplugin"]->generateSitemap();
				}
			)));

			$app->events->registerAction(new EventAction(array(
				"id" => "crispycat.SitemapPlugin.generate_on_category_edit",
				"event" => "assets.categories.set",
				"priority" => 0,
				"action" => function($app) {
					$app->vars["sitemapplugin"]->generateSitemap();
				}
			)));

			$app->events->registerAction(new EventAction(array(
				"id" => "crispycat.SitemapPlugin.generate_on_category_delete",
				"event" => "assets.categories.delete",
				"priority" => 0,
				"action" => function($app) {
					$app->vars["sitemapplugin"]->generateSitemap();
				}
			)));
		}

		public function generateSitemap() {
			global $app;

			$sitemap = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
			$sitemap .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"\n";
			$sitemap .= "\txmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"\n";
			$sitemap .= "\txsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n";

			foreach ($app->database->readRows("routes") as $route) {
				if ($route["item_id"] == "index") $url = "http://" .  $_SERVER["SERVER_NAME"] . Config::WEBROOT . "/";
				else $url = "http://" .  $_SERVER["SERVER_NAME"] . Config::WEBROOT . "/{$route["id"]}";
					
				switch ($route["view"]) {
					case "core/article":
						$article = $app("articles")->get($route["item_id"]);
						if (!$article) continue 2;
						$date = date("Y-m-d", $article->modified);
						break;
					case "core/article":
						$category = $app("categories")->get($route["item_id"]);
						if (!$category) continue 2;
						$date = date("Y-m-d", $category->modified);
						break;
					default:
						$date = date("Y-m-d");
				}

				$changefreq = $this->options["changefreq"] ?? "daily";
				$priority = ($route["item_id"] == "index") ? ($this->options["index_priority"] ?? 1) : ($this->options["priority"] ?? 0.8);

				$sitemap .= "\t<url>\n";
				$sitemap .= "\t\t<loc>$url</loc>\n";
				$sitemap .= "\t\t<lastmod>$date</lastmod>\n";
				$sitemap .= "\t\t<changefreq>$changefreq</changefreq>\n";
				$sitemap .= "\t\t<priority>$priority</priority>\n";
				$sitemap .= "\t</url>\n";
			}

			$sitemap .= "</urlset>";

			file_put_contents(Config::APPROOT . "/" . ($this->options["sitemap_filename"] ?? "sitemap.xml"), $sitemap);

			if ($this->options["generate_robots"] ?? true) {
				$robots = "User-agent: *\n";
				$robots .= "Disallow: /backend/\n";
				$robots .= "Disallow: /installer/\n";
				$robots .= "Sitemap: http://" . $_SERVER["SERVER_NAME"] . Config::WEBROOT . ($this->options["sitemap_filename"] ?? "sitemap.xml");

				file_put_contents(Config::APPROOT . "/robots.txt", $robots);
			}
		}
	}
