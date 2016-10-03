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
     * Msg class.
     * This class is for showing response about /message
     *
     * @package kakao
     * @author JJH
     */
    class Msg extends BaseClass
    {
        /**
         * Message type
         *
         * @var Msg\Message
         */
        private $message;
        /**
         * Keyboard type
         *
         * @var Keyboard
         */
        private $keyboard;
        /**
         * Is default keyboard or not.
         *
         * @var bool
         */
        private $use_default_keyboard = FALSE;

        /**
         * Constructor for Msg class.
         *
         * keyboard : null -> Subjective.
         * keyboard : TRUE -> Use default keyboard.
         * keyboard : array -> Use keyboard elements.
         *
         * @param Msg\Message $message
         * @param mixed $keyboard
         */
        public function __construct(Msg\Message $message, $keyboard = NULL)
        {
            $this->message = $message;
            if ($keyboard !== TRUE)
                $this->keyboard = $keyboard;
            else {
                include_once __DIR__ . '/../config.php';
                include_once __DIR__ . '/Keyboard.php';
                $this->keyboard = new Keyboard($GLOBALS['DEFAULT_KEYBOARD']);
                $this->use_default_keyboard = TRUE;
            }
        }

        /**
         * Check that it is valid or not.
         *
         * @return bool
         */
        public function is_valid()
        {
            if (!$this->message->is_valid()) {
                $this->invalid_msg = $this->message->invalid_msg;
                return FALSE;
            }
            if (isset($this->keyboard) && !$this->keyboard->is_valid()) {
                $this->invalid_msg = $this->keyboard->invalid_msg;
                return FALSE;
            }
            return TRUE;
        }

        /**
         * Return array version.
         *
         * @return array
         */
        public function toArray()
        {
            $result['message'] = $this->message->toArray();
            if (isset($this->keyboard) && $this->keyboard->is_objective())
                $result['keyboard'] = $this->keyboard->toArray();
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
            $result = end_line($tab_size) . "new Message(";
            $result .= $this->message->get_argument($tab_size + 1);
            $result .= end_line($tab_size) . "),";
            if ($this->use_default_keyboard === TRUE)
                $result .= end_line($tab_size) . "TRUE";
            elseif (isset($this->keyboard))
                $result .= $this->keyboard->get_class($tab_size);

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
            return "new Msg(" . $this->get_argument($tab_size + 1) . end_line($tab_size) . ")";
        }
    }
}

namespace kakao\Msg {

    use kakao\BaseClass;

    /**
     * Message class.
     * For more information, visited https://github.com/plusfriend/auto_reply#62-message.
     *
     * @package kakao
     * @author JJH
     */
    class Message extends BaseClass
    {
        /**
         * Text component
         *
         * @var string
         */
        private $text;
        /**
         * Photo component
         * If there is no photo, then it is null.
         *
         * @var Message\Photo|null
         */
        private $photo;
        /**
         * MessageButton component
         * If there is no message_button, then it is null.
         *
         * @var Message\MessageButton|null
         */
        private $message_button;

        /**
         * Constructor for Message class.
         *
         * @param $text
         * @param array|null $photo
         * @param array|null $message_button
         */
        public function __construct($text, $photo = NULL, $message_button = NULL)
        {
            $this->text = str_replace("\r\n", "\n", $text);
            $this->photo = isset($photo) ? new Message\Photo($photo) : NULL;
            $this->message_button = isset($message_button) ? new Message\MessageButton($message_button) : NULL;
        }

        /**
         * Return array version.
         *
         * @return array
         */
        public function toArray()
        {
            $result = array("text" => $this->text);
            if (isset($this->photo))
                $result["photo"] = $this->photo->toArray();
            if (isset($this->message_button))
                $result["message_button"] = $this->message_button->toArray();
            return $result;
        }

        /**
         * Check that it is valid or not.
         *
         * @return bool
         */
        public function is_valid()
        {
            if (mb_strlen($this->text, 'utf-8') > 1000) {
                $this->invalid_msg = "Maximum text length is 1000.";
                return FALSE;
            }
            if (isset($this->photo) && !$this->photo->is_valid()) {
                $this->invalid_msg = $this->photo->invalid_msg;
                return FALSE;
            }
            if (isset($this->message_button) && !$this->message_button->is_valid()) {
                $this->invalid_msg = $this->message_button->invalid_msg;
                return FALSE;
            }
            return TRUE;
        }

        /**
         * Return the all arguments with formatting.
         *
         * @param int $tab_size tab size
         * @return string
         */
        public function get_argument($tab_size = 0)
        {
            $result = end_line($tab_size) . "\"" . addslashes($this->text) . "\",";
            $result .= end_line($tab_size);
            if (isset($this->photo))
                $result .= "array(" . $this->photo->get_argument($tab_size + 1) . end_line($tab_size) . "),";
            else
                $result .= "NULL,";
            $result .= end_line($tab_size);
            if (isset($this->message_button))
                $result .= "array(" . $this->message_button->get_argument($tab_size + 1) . end_line($tab_size) . ")";
            else
                $result .= "NULL";
            return $result;
        }
    }
}

namespace kakao\Msg\Message {

    use kakao\BaseClass;

    /**
     * Photo class.
     * For more information, visited https://github.com/plusfriend/auto_reply#63-photo.
     *
     * @package kakao
     * @author JJH
     */
    class Photo extends BaseClass
    {
        /**
         * Photo url
         *
         * @var string
         */
        private $url;
        /**
         * Photo width
         *
         * @var int
         */
        private $width;
        /**
         * Photo height
         *
         * @var int
         */
        private $height;

        /**
         * Constructor for Photo class.
         *
         * @param array $photo
         */
        public function __construct($photo)
        {
            $this->url = $photo[0];
            $this->width = intval($photo[1]);
            $this->height = intval($photo[2]);
            $this->invalid_msg = "Only jpeg, png are supported.";
        }

        /**
         * Check that it is valid or not.
         *
         * @return bool
         */
        public function is_valid()
        {
            if (strpos($this->url, 'http') === 0 && !ini_get('allow_url_fopen')) {
                $ext = strtolower(substr($this->url, strrpos($this->url, '.') + 1));
                return in_array($ext, array("png", "jpg", "jpeg"));
            }
            $size = @getimagesize($this->url);
            if ($size === false) {
                $this->invalid_msg = "Invalid photo url.";
                return false;
            }
            $ALLOWED_MIME = array("image/png", "image/jpeg");
            return in_array($size['mime'], $ALLOWED_MIME);
        }

        /**
         * Return array version.
         *
         * @return array
         */
        public function toArray()
        {
            return array("url" => $this->url, "width" => $this->width, "height" => $this->height);
        }

        /**
         * Return the all arguments with formatting.
         *
         * @param int $tab_size tab size
         * @return string
         */
        public function get_argument($tab_size = 0)
        {
            $result = end_line($tab_size) . '"' . addslashes($this->url) . "\",";
            $result .= end_line($tab_size) . $this->width . ',';
            $result .= end_line($tab_size) . $this->height;
            return $result;
        }
    }

    /**
     * MessageButton class.
     * For more information, visited https://github.com/plusfriend/auto_reply#621-messagebutton.
     *
     * @package kakao
     * @author JJH
     */
    class MessageButton extends BaseClass
    {
        /**
         * Label text
         *
         * @var string
         */
        private $label;
        /**
         * URL path to move.
         *
         * @var string
         */
        private $url;

        /**
         * Constructor for MessageButton class.
         *
         * @param array $message_button
         */
        public function __construct($message_button)
        {
            $this->label = $message_button[0];
            $this->url = $message_button[1];
            $this->invalid_msg = "Invalid url.";
        }

        /**
         * Check that it is valid or not.
         *
         * @return bool
         */
        public function is_valid()
        {
            return !filter_var($this->url, FILTER_VALIDATE_URL) === FALSE;
        }

        /**
         * Return array version.
         *
         * @return array
         */
        public function toArray()
        {
            return array("label" => $this->label, "url" => $this->url);
        }

        /**
         * Return the all arguments with formatting.
         *
         * @param int $tab_size tab size
         * @return string
         */
        public function get_argument($tab_size = 0)
        {
            $result = end_line($tab_size) . "\"" . addslashes($this->label) . "\",";
            $result .= end_line($tab_size) . "\"" . addslashes($this->url) . "\"";
            return $result;
        }
    }
}