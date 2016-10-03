<?php
/**
 * auto_reply
 *
 * @author Jang Joonho <jhjang1005@naver.com>
 * @copyright 2016 Jang Joonho
 * @license GPLv3
 */

namespace kakao;

define("SUCCESS_CODE", 200);
define("FAILED_CODE", 400);

/**
 * Base class for all created class
 *
 * @package kakao
 * @author JJH
 */
abstract class BaseClass
{
    /**
     * Return array version.
     *
     * @return array
     */
    abstract public function toArray();

    /**
     * Return the object is valid or not.
     *
     * @return bool
     */
    abstract public function is_valid();

    /**
     * Return the all arguments with formatting.
     *
     * @param int $tab_size tab size
     * @return string
     */
    abstract public function get_argument($tab_size);

    /**
     * Implement invalid message.
     *
     * @var string
     */
    protected $invalid_msg = "-1";

    /**
     * Get invalid message.
     *
     * @return string
     */
    public function get_invalid_msg()
    {
        if ($this->invalid_msg === "-1")
            $this->is_valid();
        return $this->invalid_msg;
    }

    /**
     * Return json version.
     *
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->toArray(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}