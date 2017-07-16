<?php

namespace MyCLabs\Enum;

/**
 * Parse and return annotations for php class const
 *
 * Class ConstAnnotationsParser
 * @package MyCLabs\Enum
 */
abstract class ConstAnnotationsParser
{
    /**
     * Parses the class for constant annotations
     *
     * @param $class
     * @return mixed
     */
    public static function parseAndReturnAnnotations($class)
    {
        $constantsAnnotations = array();
        $class = new \ReflectionClass($class);
        $content = file_get_contents($class->getFileName());
        $tokens = token_get_all($content);
        $doc = null;
        $isConst = false;
        foreach($tokens as $token)
        {
            if (count($token) <= 1)
            {
                continue;
            }

            list($tokenType, $tokenValue) = $token;

            switch ($tokenType)
            {
                // ignored tokens
                case T_WHITESPACE:
                case T_COMMENT:
                    break;

                case T_DOC_COMMENT:
                    $doc = $tokenValue;
                    break;

                case T_CONST:
                    $isConst = true;
                    break;

                case T_STRING:
                    if ($isConst)
                    {
                        $annotations = array();
                        $lines = preg_split('/\R/', $doc);
                        foreach($lines as $line)
                        {
                            $line = trim($line, "/* \t\x0B\0");
                            if ($line === '')
                            {
                                continue;
                            }
                            preg_match_all("/@(\w+)\(\s*([^\(]*?)\s*\)/",$line, $match);
                            if(count($match) > 0) {
                                for($i = 0; $i < count($match[0]); $i++){
                                    $annotations[$match[1][$i]] = trim($match[2][$i], "'\"");
                                }
                            }
                        }
                        $constantsAnnotations[$tokenValue] = $annotations;
                    }
                    $doc = null;
                    $isConst = false;
                    break;

                // all other tokens reset the parser
                default:
                    $doc = null;
                    $isConst = false;
                    break;
            }
        }
        return $constantsAnnotations;
    }
}