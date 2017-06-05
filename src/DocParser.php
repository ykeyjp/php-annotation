<?php
namespace ykey\annotation;

/**
 * Class DocParser
 *
 * @package ykey\annotation
 */
class DocParser
{
    const T_UNKNOWN = 10000;
    const T_AT = 10001;
    const T_CLOSE_CURLY_BRACES = 10002;
    const T_CLOSE_PARENTHESIS = 10003;
    const T_COMMA = 10004;
    const T_EQUALS = 10005;
    const T_NAMESPACE_SEPARATOR = 10006;
    const T_OPEN_CURLY_BRACES = 10007;
    const T_OPEN_PARENTHESIS = 10008;
    const T_COLON = 10009;
    const T_OPEN_ARRAY = 10010;
    const T_CLOSE_ARRAY = 10011;
    const CUSTOM_TOKENS = [
        '@'  => self::T_AT,
        ','  => self::T_COMMA,
        '['  => self::T_OPEN_ARRAY,
        ']'  => self::T_CLOSE_ARRAY,
        '('  => self::T_OPEN_PARENTHESIS,
        ')'  => self::T_CLOSE_PARENTHESIS,
        '{'  => self::T_OPEN_CURLY_BRACES,
        '}'  => self::T_CLOSE_CURLY_BRACES,
        '='  => self::T_EQUALS,
        ':'  => self::T_COLON,
        '\\' => self::T_NAMESPACE_SEPARATOR,
    ];

    /**
     * @param string $text
     *
     * @return Annotation[]
     */
    public static function fromString(string $text): array
    {
        $text = preg_replace('#^/\*\*+\s*#', '', $text);
        $text = preg_replace('#\s*\*+/$#', '', $text);
        $text = preg_replace('#^[\s\*]+#m', '', $text);
        $text = trim($text);
        $tokens = token_get_all("<?php\n$text\n");
        foreach ($tokens as $index => $token) {
            $prevToken = $tokens[$index - 1] ?? [T_WHITESPACE, "\n", 1];
            if (is_string($token)) {
                $tokenName = self::CUSTOM_TOKENS[$token] ?? self::T_UNKNOWN;
                $line = $prevToken[2] + ($prevToken[1] === "\n" ? 1 : 0);
                $token = [$tokenName, $token, $line];
                $tokens[$index] = $token;
            }
        }

        return self::parseNames($tokens);
    }

    /**
     * @param $tokens
     *
     * @return Annotation[]
     */
    private static function parseNames($tokens): array
    {
        $names = [];
        $countToken = count($tokens);
        for ($index = 0; $index < $countToken; ++$index) {
            $token = $tokens[$index];
            if ($token[0] === self::T_AT) {
                $ptoken = $tokens[$index - 1] ?? null;
                $ntoken = $tokens[$index + 1] ?? null;
                if (is_null($ptoken) || is_null($ntoken)) {
                    continue;
                }
                if (($ptoken[0] === T_OPEN_TAG || $ptoken[1] === "\n") && $ntoken[0] === T_STRING) {
                    $name = $ntoken[1];
                    $index = $index + 2;
                    $arguments = self::parseCollection($tokens, $index);
                    $names[] = new Annotation($name, $arguments);
                }
            }
        }

        return $names;
    }

    /**
     * @param array $tokens
     * @param int   $offset
     *
     * @return array
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private static function parseCollection(array $tokens, int &$offset): array
    {
        switch ($tokens[$offset][0]) {
            case self::T_OPEN_PARENTHESIS:
                $endBrace = self::T_CLOSE_PARENTHESIS;
                break;
            case self::T_OPEN_ARRAY:
                $endBrace = self::T_CLOSE_ARRAY;
                break;
            case self::T_OPEN_CURLY_BRACES:
                $endBrace = self::T_CLOSE_CURLY_BRACES;
                break;
            default:
                return [];
                break;
        }
        $argIndex = 0;
        $args = [];
        $countToken = count($tokens);
        for ($index = $offset + 1; $index < $countToken; ++$index) {
            $token = $tokens[$index] ?? null;
            if (is_null($token)) {
                break;
            } elseif ($token[0] === $endBrace) {
                break;
            } elseif ($token[0] === T_WHITESPACE) {
                continue;
            } elseif ($token[0] === self::T_COMMA) {
                ++$argIndex;
            } elseif ($token[0] === self::T_OPEN_ARRAY || $token[0] === self::T_OPEN_CURLY_BRACES) {
                $args[$argIndex][] = self::parseCollection($tokens, $index);
            } else {
                if ($token[0] === T_CONSTANT_ENCAPSED_STRING) {
                    $args[$argIndex][] = substr($token[1], 1, -1);
                } elseif ($token[0] === T_LNUMBER) {
                    $args[$argIndex][] = (int)$token[1];
                } elseif ($token[0] === T_DNUMBER) {
                    $args[$argIndex][] = (float)$token[1];
                } elseif (preg_match('/(?:(true)|(false))/i', $token[1], $match)) {
                    $args[$argIndex][] = !empty($match[1]);
                } elseif (strtolower($token[1]) === 'null') {
                    $args[$argIndex][] = null;
                } else {
                    $args[$argIndex][] = $token[1];
                }
            }
        }
        $offset = $index;
        $arguments = [];
        foreach ($args as $key => $arg) {
            if (count($arg) === 1) {
                $arguments[$key] = $arg[0];
            } elseif (count($arg) === 3) {
                $arguments[$arg[0]] = $arg[2];
            }
        }

        return $arguments;
    }
}
