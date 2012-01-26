<?php

	class BioBox extends Modules
	{
		static function __install()
		{
			$config = Config::current();
			$config->set('biobox_title', "This Site", true);
			$config->set('biobox_text', "Proudly Powered by Chyrp", true);
		}
		
		static function __uninstall($confirm)
		{
			if($confirm)
			{
				$config = Config::current();
				$config->remove('biobox_title');
				$config->remove('biobox_text');
			}
		}

		public function settings_nav($navs)
		{
			if(Visitor::current()->group->can("change_settings"))
				$navs["biobox_settings"] = array("title" => __("Bio Box", "biobox"));

			return $navs;
		}

		public function admin_biobox_settings($admin)
		{
			$config = Config::current();
			if(empty($_POST)) {
				return $admin->display("biobox_settings");
			}

			if(($config->set("biobox_title", $_POST['biobox_title'])) && ($config->set("biobox_text", $_POST['biobox_text'])))
				Flash::notice(__("Settings updated."), "/admin/?action=biobox_settings");
		}
		
		public function sidebar()
		{
			$config = Config::current();
			echo '                <h1>'.$config->biobox_title.'</h1>';
			if (in_array("textilize",$config->enabled_modules)) {
			    $text = Textilize::textile($config->biobox_text);
		    } else {
		        $text = $config->biobox_text;
			    if (in_array("markdown",$config->enabled_modules)) {
			        $text = Markdown::markdownify($text);
		        }
			    if (in_array("smartypants",$config->enabled_modules)) {
			        $text = Smartypants::smartify($text);
		        }
	        }
			echo '                '.$text;
		}
	}