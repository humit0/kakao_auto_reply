<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

namespace {
    include_once __DIR__ . '/BaseClass.php';
}

namespace kakao {
    /**
     * Keyboard class.
     * For more information, visited https://github.com/plusfriend/auto_reply#61-keyboard.
     *
     * @package kakao
     * @author JJH
     */
    class Keyboard extends BaseClass
    {
        /**
         * Keyboard type
         *
         * @var string
         */
        private $type = "buttons";
        /**
         * Objective responses list
         *
         * @var array|null
         */
        private $buttons;

        /**
         * Constructor for Keyboard class.
         * If the button is null, then it is Subjective.
         * Else, it is Objective.
         *
         * @param mixed $button
         */
        public function __construct($button = NULL)
        {
            if (is_string($button))
                $this->buttons = array($button);
            elseif (is_array($button))
                $this->buttons = $button;
            elseif (is_null($button)) {
                $this->type = "text";
                $this->buttons = "";
            } else
                $this->buttons = NULL;
            $this->invalid_msg = "Invalid Keyboard format.";
        }

        /**
         * Check that it is objective or not.
         *
         * @return bool
         */
        public function is_objective()
        {
            return $this->type === "buttons";
        }

        /**
         * Check that it is valid or not.
         *
         * @return bool
         */
        public function is_valid()
        {
            return !is_null($this->buttons);
        }

        /**
         * Return array version.
         *
         * @return array
         */
        public function toArray()
        {
            $result["type"] = $this->type;
            if (is_array($this->buttons))
                $result["buttons"] = $this->buttons;
            return $result;
        }

        /**
         * Return the all arguments with formatting.
         *
         * @param int $tab_size tab size
         * @return string
         */
        public function get_argument($tab_size = 0)
        {
            $eol = end_line($tab_size);
            $result = $eol;
            if (is_array($this->buttons))
                $result .= '"' . implode('",' . $eol . '"', array_map('addslashes', $this->buttons)) . '"';
            return $result;
        }

        /**
         * Return php version of class with formatting.
         *
         * @param int $tab_size tab size
         * @return string
         */
        public function get_class($tab_size = 0)
        {
            $result = end_line($tab_size) . "new Keyboard(";
            if (is_array($this->buttons)) {
                $result .= end_line($tab_size + 1) . "array(" . $this->get_argument($tab_size + 2);
                $result .= end_line($tab_size + 1) . ")";
            } else {
                $result .= end_line($tab_size + 1) . "NULL";
            }
            $result .= end_line($tab_size) . ")";
            return $result;
        }
    }
}