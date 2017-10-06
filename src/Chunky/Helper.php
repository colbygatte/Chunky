<?php

namespace ColbyGatte\Chunky;

class Helper
{
    /**
     * @var \ColbyGatte\Chunky\Helper
     */
    protected static $helper;

    /**
     * @return \ColbyGatte\Chunky\Helper
     */
    public static function instance()
    {
        return static::$helper ?: static::$helper = new Helper;
    }

    /**
     * Parses a raw string into a tags array.
     *
     * @param $string
     *
     * @return array
     * @throws \Exception
     */
    public function stringToTags($string)
    {
        $tags = [];

        foreach (explode('|', $string) as $tagKeyValueUnParsed) {
            $keyValue = explode(':', $tagKeyValueUnParsed, 2);

            if (count($keyValue) != 2) {
                throw new \Exception("Error parsing tags: $string");
            }

            $tags[$keyValue[0]] = $keyValue[1];
        }

        return $tags;
    }

    public function tagsToString($tags)
    {
        $tagData = [];

        foreach ($tags as $key => $value) {
            $tagData[] = "$key:$value";
        }

        return implode('|', $tagData);
    }
}