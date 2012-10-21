<?php
    require "lib/email_obfuscator.php";

    class Obfuscator extends Modules {
        public function __init() {
           $this->addAlias("markup_text", "checkmail");
           $this->addAlias("markup_post_text","checkmail");
           $this->addAlias("preview", "checkmail");
           $this->addAlias("preview_post", "checkmail");
        }
        public function checkmail($text) {
            return obfuscateEmails($text);
        }
    }
