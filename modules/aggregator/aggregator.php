<?php
	class Aggregator extends Modules {
		static function __install() {
			$config = Config::current();
			$config->set("last_aggregation", 0);
			$config->set("aggregate_every", 30);
			$config->set("disable_aggregation", false);
			$config->set("aggregation_feeds", array());

			Group::add_permission("add_aggregate", "Add Aggregate");
			Group::add_permission("edit_aggregate", "Edit Aggregate");
			Group::add_permission("delete_aggregate", "Delete Aggregate");
		}

		static function __uninstall() {
			$config = Config::current();
			$config->remove("last_aggregation");
			$config->remove("aggregate_every");
			$config->remove("disable_aggregation");
			$config->remove("aggregation_feeds");

			Group::remove_permission("add_aggregate");
			Group::remove_permission("edit_aggregate");
			Group::remove_permission("delete_aggregate");
		}

		public function runtime() {
			if (Route::current()->action != "index" or JAVASCRIPT or ADMIN) return;

			$config = Config::current();
			if ($config->disable_aggregation or time() - $config->last_aggregation < ($config->aggregate_every * 60))
				return;

			$aggregation_feeds = $config->aggregation_feeds;
			foreach ($config->aggregation_feeds as $name => $feed) {
				$xml_contents = preg_replace(array("/<(\/?)dc:date>/", "/xmlns=/"),
				                             array("<\\1date>", "a="),
				                             get_remote($feed["url"]));
				$xml = simplexml_load_string($xml_contents, "SimpleXMLElement", LIBXML_NOCDATA);

				if ($xml == false)
					continue;

				# Flatten namespaces recursively
				$this->flatten($xml);

				$items = array();

				if (isset($xml->entry))
					foreach ($xml->entry as $entry)
						array_unshift($items, $entry);
				elseif (isset($xml->item))
					foreach ($xml->item as $item)
						array_unshift($items, $item);
				else
					foreach ($xml->channel->item as $item)
						array_unshift($items, $item);

				foreach ($items as $item) {
					$date = fallback($item->pubDate, fallback($item->date, fallback($item->published, 0, true), true), true);

					if (strtotime($date) > $feed["last_updated"]) {
						$data = array("aggregate" => $name);
						foreach ($feed["data"] as $attr => $field)
							$data[$attr] = (!empty($field)) ? $this->parse_field($field, $item) : "" ;

						$_POST['feather'] = $feed["feather"];
						$_POST['user_id'] = $feed["author"];
						Post::add($data);

						$aggregation_feeds[$name]["last_updated"] = strtotime($date);
					}
				}
			}
			$config->set("aggregation_feeds", $aggregation_feeds);
			$config->set("last_aggregation", time());
		}

		public function admin_manage_aggregates($admin) {
			$aggregates = array();

			foreach (Config::current()->aggregation_feeds as $name => $aggregate)
				$aggregates[] = array_merge(array("name" => $name), array("user" => new User($aggregate["author"])), $aggregate);

			$admin->context["aggregates"] = new Paginator($aggregates, 25, "page", false);
		}
		public function manage_nav($navs) {
			if (!Visitor::current()->group()->can("edit_aggregate", "delete_aggregate"))
				return $navs;

			$navs["manage_aggregates"] = array("title" => __("Aggregates", "aggregator"),
			                                   "selected" => array("edit_aggregate", "delete_aggregate", "new_aggregate"));

			return $navs;
		}

		public function manage_nav_pages($pages) {
			array_push($pages, "manage_aggregates", "edit_aggregate", "delete_aggregate", "new_aggregate");
			return $pages;
		}

		public function manage_nav_show($possibilities) {
			$possibilities[] = Visitor::current()->group()->can("edit_aggregate", "delete_aggregate");
			return $possibilities;
		}

		public function determine_action($action) {
			if ($action != "manage") return;

			if (Visitor::current()->group()->can("edit_aggregate", "delete_aggregate"))
				return "manage_aggregates";
		}

		public function settings_nav($navs) {
			if (Visitor::current()->group()->can("change_settings"))
				$navs["aggregation_settings"] = array("title" => __("Aggregation", "aggregator"));

			return $navs;
		}

		private function flatten(&$start) {
			foreach ($start as $key => $val) {
				if (count($val) and !is_string($val)) {
					foreach ($val->getNamespaces(true) as $namespace => $url) {
						if (empty($namespace))
							continue;

						foreach ($val->children($url) as $attr => $child) {
							$name = $namespace.":".$attr;
							$val->$name = $child;
							foreach ($child->attributes() as $attr => $value)
								$val->$name->addAttribute($attr, $value);
						}
					}

					$this->flatten($val);
				}
			}
		}

		static function image_from_content($html) {
			preg_match("/img src=('|\")([^ \\1]+)\\1/", $html, $image);
			return $image[2];
		}

		static function upload_image_from_content($html) {
			return upload_from_url(self::image_from_content($html));
		}

		public function parse_field($value, $item) {
			if (preg_match("/^([a-z0-9:\/]+)$/", $value)) {
				$xpath = $item->xpath($value);
				return html_entity_decode($xpath[0], ENT_QUOTES, "utf-8");
			}

			if (preg_match("/feed\[(.+)\]\.attr\[([^\]]+)\]/", $value, $matches)) {
				$xpath = $item->xpath($matches[1]);
				$value = str_replace($matches[0],
				                     html_entity_decode($xpath[0]->attributes()->$matches[2],
				                                        ENT_QUOTES,
				                                        "utf-8"),
				                     $value);
			}

			if (preg_match("/feed\[(.+)\]/", $value, $matches)) {
				$xpath = $item->xpath($matches[1]);
				$value = str_replace($matches[0],
				                     html_entity_decode($xpath[0], ENT_QUOTES, "utf-8"),
				                     $value);
			}

			if (preg_match_all("/call:([^\(]+)\((.+)\)/", $value, $calls))
				foreach ($calls[0] as $index => $full) {
					$function = $calls[1][$index];
					$arguments = explode(" || ", $calls[2][$index]);

					$value = str_replace($full,
					                     call_user_func_array($function, $arguments),
					                     $value);
				}

			return $value;
		}

		public function admin_aggregation_settings($admin) {
			if (!Visitor::current()->group()->can("change_settings"))
				show_403(__("Access Denied"), __("You do not have sufficient privileges to change settings."));

			if (empty($_POST))
				return;

			if (!isset($_POST['hash']) or $_POST['hash'] != Config::current()->secure_hashkey)
				show_403(__("Access Denied"), __("Invalid security key."));

			$config = Config::current();
			$set = array($config->set("aggregate_every", $_POST['aggregate_every']),
			             $config->set("disable_aggregation", !empty($_POST['disable_aggregation'])),
			             $config->set("aggregation_author", $_POST['aggregation_author']));

			if (!in_array(false, $set))
				Flash::notice(__("Settings updated."), "/admin/?action=aggregation_settings");
		}

		public function admin_new_aggregate($admin) {
			$admin->context["users"] = User::find();

			if (empty($_POST))
				return;

			$config = Config::current();

			$aggregate = array("url" => $_POST['url'],
			                   "last_updated" => 0,
			                   "feather" => $_POST['feather'],
			                   "author" => $_POST['author'],
			                   "data" => Horde_Yaml::load($_POST['data']));

			$config->aggregation_feeds[$_POST['name']] = $aggregate;
			$config->set("aggregation_feeds", $config->aggregation_feeds);

			Flash::notice(__("Aggregate created.", "aggregator"), "/admin/?action=manage_aggregates");
		}

		public function admin_edit_aggregate($admin) {
			if (empty($_GET['id']))
				error(__("No ID Specified"), __("An ID is required to delete an aggregate.", "aggregator"));

			if (!Visitor::current()->group()->can("edit_aggregate"))
				show_403(__("Access Denied"), __("You do not have sufficient privileges to delete this aggregate.", "aggregator"));

			$admin->context["users"] = User::find();

			$config = Config::current();

			$_GET['id'] = urldecode($_GET['id']);

			$aggregate = $config->aggregation_feeds[$_GET['id']];

			$admin->context["aggregate"] = array("name" => $_GET['id'],
			                                     "url" => $aggregate["url"],
			                                     "feather" => $aggregate["feather"],
			                                     "author" => $aggregate["author"],
			                                     "data" => preg_replace("/^---\n/", "", Horde_Yaml::dump($aggregate["data"])));

			if (empty($_POST))
				return;

			if (!isset($_POST['hash']) or $_POST['hash'] != Config::current()->secure_hashkey)
				show_403(__("Access Denied"), __("Invalid security key."));

			$aggregate = array("url" => $_POST['url'],
			                   "last_updated" => 0,
			                   "feather" => $_POST['feather'],
			                   "author" => $_POST['author'],
			                   "data" => Horde_Yaml::load($_POST['data']));

			unset($config->aggregation_feeds[$_GET['id']]);
			$config->aggregation_feeds[$_POST['name']] = $aggregate;

			$config->set("aggregation_feeds", $config->aggregation_feeds);

			Flash::notice(__("Aggregate updated.", "aggregator"), "/admin/?action=manage_aggregates");
		}

		public function admin_delete_aggregate($admin) {
			if (empty($_GET['id']))
				error(__("No ID Specified"), __("An ID is required to delete an aggregate.", "aggregator"));

			if (!Visitor::current()->group()->can("delete_aggregate"))
				show_403(__("Access Denied"), __("You do not have sufficient privileges to delete this aggregate.", "aggregator"));

			$config = Config::current();

			$_GET['id'] = urldecode($_GET['id']);

			$aggregate = $config->aggregation_feeds[$_GET['id']];

			$admin->context["aggregate"] = array("name" => $_GET['id'],
			                                     "url" => $aggregate["url"]);
		}

		public function admin_destroy_aggregate($admin) {
			if (empty($_POST['id']))
				error(__("No ID Specified"), __("An ID is required to delete an aggregate.", "aggregator"));

			if ($_POST['destroy'] == "bollocks")
				redirect("/admin/?action=manage_aggregates");

			if (!isset($_POST['hash']) or $_POST['hash'] != Config::current()->secure_hashkey)
				show_403(__("Access Denied"), __("Invalid security key."));

			if (!Visitor::current()->group()->can("delete_aggregate"))
				show_403(__("Access Denied"), __("You do not have sufficient privileges to delete this aggregate.", "aggregator"));

			$config = Config::current();
			unset($config->aggregation_feeds[$_POST['id']]);
			$config->set("aggregation_feeds", $config->aggregation_feeds);
			Flash::notice(__("Aggregate deleted.", "aggregator"), "/admin/?action=manage_aggregates");
		}

		public function help_aggregation_syntax() {
			global $title, $body;
			$title = __("Post Values", "aggregator");
			$body = "<p>".__("Use <a href=\"http://yaml.org/\">YAML</a> to specify what post attribute holds what value of the feed entry.", "aggregator")."</p>";

			$body.= "<h2>".__("XPath", "aggregator")."</h2>";
			$body.= "<cite><strong>".__("Usage")."</strong>: <code>feed[xp/ath]</code></cite>\n";
			$body.= "<p>".__("You can use XPath to navigate the feed and find the correct attribute.", "aggregator")."</p>";

			$body.= "<h2>".__("Attributes", "aggregator")."</h2>";
			$body.= "<cite><strong>".__("Usage")."</strong>: <code>feed[xp/ath].attr[foo]</code></cite>\n";
			$body.= "<p>".__("To get the attribute of an element, use XPath to find it and the <code>.attr[]</code> syntax to grab an attribute.", "aggregator")."</p>";

			$body.= "<h2>".__("Functions", "aggregator")."</h2>";
			$body.= "<cite><strong>".__("Usage")."</strong>: <code>call:foo_function(feed[foo] || feed[arg2])</code></cite>\n";
			$body.= "<p>".__("To call a function and use its return value for the post's value, use <code>call:</code>. Separate arguments with <code> || </code>.")."</p>";
			$body.= "<p>".__("The Aggregator module provides a couple helper functions:")."</p>";
			$body.= "<cite><strong>".__("To upload an image from the content", "aggregator")."</strong>: <code>call:Aggregator::upload_image_from_content(feed[content])</code></cite>";
			$body.= "<cite><strong>".__("To get the URL of an image in the content", "aggregator")."</strong>: <code>call:Aggregator::image_from_content(feed[content])</code></cite>";

			$body.= "<h2>".__("Example", "aggregator")."</h2>";
			$body.= "<p>".__("From the Photo feather:")."</pre>";
			$body.= "<pre><code>filename: call:upload_from_url(feed[link].attr[href])\ncaption: feed[description]</code></pre>";
		}
	}