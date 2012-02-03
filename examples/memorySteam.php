<?php

/**
 * This class allow to write a variable content into memory
 */
class MemoryStream {
    var $position;
    var $varname;
    var $content;

    function stream_open($path, $mode, $options, &$opened_path)
    {
        $url = parse_url($path);
        $this->varname = $url["host"];
        $this->position = 0;

        return true;
    }

    function stream_read($count)
    {
        $ret = substr($this->content, $this->position, $count);
        $this->position += strlen($ret);
        return $ret;
    }

    function stream_write($data)
    {
        $left = substr($this->content, 0, $this->position);
        $right = substr($this->content, $this->position + strlen($data));
        $this->content = $left . $data . $right;
        $this->position += strlen($data);
        return strlen($data);
    }

    function stream_tell()
    {
        return $this->position;
    }

    function stream_eof()
    {
        return $this->position >= strlen($this->content);
    }

    function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case SEEK_SET:
                if ($offset < strlen($this->content) && $offset >= 0) {
                     $this->position = $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            case SEEK_CUR:
                if ($offset >= 0) {
                     $this->position += $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            case SEEK_END:
                if (strlen($this->content) + $offset >= 0) {
                     $this->position = strlen($this->content) + $offset;
                     return true;
                } else {
                     return false;
                }
                break;

            default:
                return false;
        }
    }

    function stream_metadata($path, $option, $var)
    {
        if($option == STREAM_META_TOUCH) {
            $url = parse_url($path);
            $varname = $url["host"];
            if(!isset($this->content)) {
                $this->content = '';
            }
            return true;
        }
        return false;
    }
}