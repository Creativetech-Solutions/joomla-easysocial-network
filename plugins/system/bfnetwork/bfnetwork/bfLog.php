<?php

/**
 * @package   Blue Flame Network (bfNetwork)
 * @copyright Copyright (C) 2011, 2012, 2013, 2014, 2015, 2016 Blue Flame IT Ltd. All rights reserved.
 * @license   GNU General Public License version 3 or later
 * @link      https://myJoomla.com/
 * @author    Phil Taylor / Blue Flame IT Ltd.
 *
 * bfNetwork is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * bfNetwork is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this package.  If not, see http://www.gnu.org/licenses/
 */
class bfLog
{
    const FILE = '/tmp/log.tmp';

    /**
     * To log do bfLog::log('something');
     *
     * @param      $msg
     * @param bool $truncate
     */
    static public function log($msg, $truncate = FALSE)
    {
        /**
         * I dont think we actually need to be logging anymore at all - except on crappy webhosts maybe?
         */

        if (_BF_LOG == FALSE) return;
        if (file_exists(dirname(__FILE__) . bfLog::FILE)) {
            $logSize = number_format(filesize(dirname(__FILE__) . bfLog::FILE) / 1024 / 1024, 2);
            if ($logSize > 10) {
                $truncate = TRUE;
            }
        }
        $template = '%s  | %s | %s';
        $msg      = sprintf($template,
                            self::getTimestamp(),

                            self::getTimerStats(),
                            $msg);
        if (TRUE === $truncate) {
            bfLog::truncate();
        }
        file_put_contents(dirname(__FILE__) . bfLog::FILE, $msg . PHP_EOL, FILE_APPEND);
    }

    static public function getTimestamp()
    {
        return date('H:i:s');
    }

    static public function convert($size)
    {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');

        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    /**
     * @return string
     */
    static public function getTimerStats()
    {
        /* @var $timer bfTimer */
        $timer = bfTimer::getInstance();

        return number_format($timer->getTimeLeft(), 2) . '/' . $timer->getMaxTime() . '/' . ini_get('max_execution_time');
    }

    /**
     *
     */
    static public function init()
    {
        bfLog::checkPermissions();
    }

    /**
     * Require all we need to work
     */
    static public function checkPermissions()
    {
        // attempt to ensure our tmp folder is writable
        if (!is_writeable(dirname(__FILE__) . '/tmp')) {
            @chmod(dirname(__FILE__) . '/tmp', 0755);
        }

        // Argh!
        if (!is_writeable(dirname(__FILE__) . '/tmp')) {
            @chmod(dirname(__FILE__) . '/tmp', 0777);
        }

        // Give Up!
        if (!is_writeable(dirname(__FILE__) . '/tmp')) {
            die('Our ' . dirname(__FILE__) . '/tmp folder on your site is not writable!');
        }

        // attempt to ensure our folder is writable
        if (!is_writeable(dirname(__FILE__))) {
            @chmod(dirname(__FILE__), 0755);
        }

        // Argh!
        if (!is_writeable(dirname(__FILE__))) {
            @chmod(dirname(__FILE__), 0777);
        }

        // Give Up!
        if (!is_writeable(dirname(__FILE__))) {
            die(dirname(__FILE__) . '/ folder not writeable');
        }
    }

    /**
     *
     */
    static public function truncate()
    {
        bflog::checkPermissions();

        @unlink('tmp/log.tmp');
        bfLog::log('Log file truncated');

        // populate the config into the log
        bfLog::log('PHP Max Memory = ' . ini_get('memory_limit'));
        bfLog::log('PHP ini_setted Max Time = ' . ini_get('max_execution_time'));
        bfLog::log('PHP bfTimer Max Time = ' . bfTimer::getInstance()
                                                      ->getMaxTime());
    }


    /**
     * bfLog::getTail();
     *
     * @param string $filename
     * @param int    $n
     *
     * @return array
     */
    static public function getTail($filename = NULL, $n = 25)
    {
        if (NULL === $filename) {
            $filename = dirname(__FILE__) . bfLog::FILE;
        }

        $buffer_size = 512;

        $fp = fopen($filename, 'r');
        if (!$fp) return array();

        fseek($fp, 0, SEEK_END);
        $pos = ftell($fp);

        $input      = '';
        $line_count = 0;

        while ($line_count < $n + 1) {

            // read the previous block of input
            $read_size = $pos >= $buffer_size ? $buffer_size : $pos;
            fseek($fp, $pos - $read_size, SEEK_SET);

            // prepend the current block, and count the new lines
            $input      = fread($fp, $read_size) . $input;
            $line_count = substr_count(ltrim($input), "\n");

            // if $pos is == 0 we are at start of file
            $pos -= $read_size;
            if (!$pos) break;
        }

        // close the file pointer
        fclose($fp);

        // return the last 50 lines found
        return array_reverse(array_slice(explode("\n", rtrim($input)), -$n));
    }
}
