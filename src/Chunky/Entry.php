<?php

namespace ColbyGatte\Chunky;

/**
 * @package ColbyGatte\Chunky
 */
class Entry
{
    use GetHelper;
    
    /**
     * @var string
     */
    protected $chunk;
    
    /**
     * @var array
     */
    protected $tags = [];
    
    /**
     * @var \ColbyGatte\Chunky\Page
     */
    protected $page;
    
    /**
     * @var string Timestamp
     */
    protected $timestamp;
    
    /**
     * @var \ColbyGatte\Chunky\SearchReport[]
     */
    protected $searchReports = [];
    
    /**
     * Chunk constructor.
     */
    public function __construct()
    {
    }
    
    /**
     * @return string
     */
    public function getChunk()
    {
        return $this->chunk;
    }
    
    /**
     * @param string $chunk
     *
     * @return $this
     */
    public function setChunk($chunk)
    {
        $this->chunk = $chunk;
        
        return $this;
    }
    
    /**
     * Tags are ONLY set from here to ensure future changes can be made seamlessly.
     *
     * @param $tag
     * @param null $value
     *
     * @return $this
     */
    public function setTag($tag, $value = null)
    {
        if (is_array($tag)) {
            foreach ($tag as $_tag => $value) {
                $this->setTag($_tag, $value);
            }
        } else {
            $tag = preg_replace('/[^\w0-9\-]/', '', strtolower($tag));
            
            $value = preg_replace('/[^\w0-9\-%#@\.\h]/', '', $value);
            
            $this->emitTagInfo($tag, $value);
            
            $this->tags[$tag] = $value;
        }
        
        return $this;
    }
    
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        
        return $this;
    }
    
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    public function toArray()
    {
        return [
            $this->chunk,
            $this->getHelper()->tagsToString($this->tags)
        ];
    }
    
    public function isTagEqual($tag, $equalTo)
    {
        $tagValue = $this->getTag($tag);
        
        if ($tagValue === false) {
            return false;
        }
        
        return $tagValue == $equalTo;
    }
    
    public function getTags()
    {
        return $this->tags;
    }
    
    /**
     * Tags are ONLY retrieved from this function to ensure future changes can be made seamlessly.
     *
     * @param string $tag
     *
     * @return false|mixed
     */
    public function getTag($tag)
    {
        return isset($this->tags[$tag]) ? $this->tags[$tag] : false;
    }
    
    /**
     * @return \ColbyGatte\Chunky\SearchReport
     */
    public function latestSearchReport()
    {
        return end($this->searchReports);
    }
    
    public function newSearchReport()
    {
        $this->searchReports[] = $searchReport = new SearchReport;
        
        return $searchReport;
    }
    
    /**
     * Called any time a tag is set.
     *
     * @param string $tag
     * @param string $value
     */
    protected function emitTagInfo($tag, $value)
    {
    }
}