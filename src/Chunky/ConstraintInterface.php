<?php

namespace ColbyGatte\Chunky;

abstract class ConstraintInterface
{
    /**
     * @var \ColbyGatte\Chunky\Page
     */
    protected $page;

    /**
     * @var \ColbyGatte\Chunky\Entry
     */
    protected $entry;

    /**
     * Used in @see ConstraintInterface::tagIsEqual()
     *
     * @var bool
     */
    protected $strictComparison = false;

    /**
     * Called before the test is ran.
     *
     * @param \ColbyGatte\Chunky\Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * @param \ColbyGatte\Chunky\Entry $entry
     */
    public function setEntry($entry)
    {
        $this->entry = $entry;
    }

    /**
     * @param      $tag
     * @param      $value
     * @param bool $strictComparison Will override the class-value strictComparison for this test only.
     *
     * @return bool
     */
    public function tagIsEqual($tag, $value, $strictComparison = null)
    {
        $strictComparison = $strictComparison ?: $this->strictComparison;

        if ($actualValue = $this->entry->getTag($tag)) {
            return $strictComparison ? $value === $actualValue : $value == $actualValue;
        }

        return false;
    }

    /**
     * @param string $tag
     * @param string $regex
     *
     * @return bool
     */
    public function tagPregMatches($tag, $regex)
    {
        return ($value = $this->entry->getTag($tag)) && preg_match($regex, $value);
    }

    /**
     * @param int $timestamp
     *
     * @return bool
     */
    public function entryIsOlderThan($timestamp)
    {
        return $this->entry->getTimestamp() < $timestamp;
    }

    /**
     * @return bool
     */
    abstract public function passesTest();

    /**
     * Optional identifying term for the constraint. Defaults to the full class name.
     *
     * @return string
     */
    public function shortName()
    {
        return static::class;
    }
}