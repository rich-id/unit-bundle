<?php declare(strict_types=1);

namespace RichCongress\Bundle\UnitBundle\TextUi;

/**
 * Class Output
 *
 * @package   RichCongress\Bundle\UnitBundle\TextUi
 * @author    Nicolas Guilloux <nguilloux@richcongress.com>
 * @copyright 2014 - 2019 RichCongress (https://www.richcongress.com)
 */
class Output
{
    /**
     * Ansi cursor movement
     */
    public const ESC = "\x1b[";
    public const GO_UP = "\033[1A";
    public const GO_DOWN = "\033[1B";
    public const GO_LINE_BEGINNING = "\r";
    public const SAVE_POSITION = "\033[s";
    public const RESTORE_POSITION = "\033[u";

    /**
     * Ansi Text Colors
     */
    public const COLOR_UNSET = '0';
    public const COLOR_BLACK = '0;30';
    public const COLOR_DARK_GRAY = '1;30';
    public const COLOR_BLUE = '0;34';
    public const COLOR_LIGHT_BLUE = '1;34';
    public const COLOR_GREEN = '0;32';
    public const COLOR_LIGHT_GREEN = '1;32';
    public const COLOR_CYAN = '0;36';
    public const COLOR_LIGHT_CYAN = '1;36';
    public const COLOR_RED = '0;31';
    public const COLOR_LIGHT_RED = '1;31';
    public const COLOR_PURPLE = '0;35';
    public const COLOR_LIGHT_PURPLE = '1;35';
    public const COLOR_BROWN = '0;33';
    public const COLOR_YELLOW = '1;33';
    public const COLOR_LIGHT_GRAY = '0;37';
    public const COLOR_WHITE = '1;37';

    /**
     * Ansi Background Colors
     */
    public const BG_COLOR_BLACK = '40';
    public const BG_COLOR_RED = '41';
    public const BG_COLOR_GREEN = '42';
    public const BG_COLOR_YELLOW = '43';
    public const BG_COLOR_BLUE = '44';
    public const BG_COLOR_MAGENTA = '45';
    public const BG_COLOR_CYAN = '46';
    public const BG_COLOR_LIGHT_GRAY = '47';

    /**
     * Characters
     */
    public const CHAR_CHECK = '✓';

    /**
     * @param string      $message
     * @param string|null $color
     * @param string|null $backgroundColor
     *
     * @return string
     */
    public static function print(string $message, string $color = null, string $backgroundColor = null): string
    {
        $output = self::genAnsiColor($color ?? self::COLOR_UNSET);

        if ($backgroundColor !== null) {
            $output .= self::genAnsiColor($backgroundColor);
        }

        $output .= $message . self::genAnsiColor(self::COLOR_UNSET);

        return $output;
    }

    /**
     * @param string $ansiColor
     *
     * @return string
     */
    protected static function genAnsiColor(string $ansiColor): string
    {
        return "\033[" . $ansiColor . 'm';
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public static function success(string $message): string
    {
        return self::print($message, self::COLOR_LIGHT_GREEN);
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public static function danger(string $message): string
    {
        return self::print($message, self::COLOR_LIGHT_RED);
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public static function error(string $message): string
    {
        return self::print($message, self::COLOR_WHITE, self::BG_COLOR_RED);
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public static function warning(string $message): string
    {
        return self::print($message, self::COLOR_YELLOW);
    }

    /**
     * @param string $message
     *
     * @return string
     */
    public static function info(string $message): string
    {
        return self::print($message, self::COLOR_LIGHT_BLUE);
    }
}
