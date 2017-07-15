<?php

namespace ColbyGatte\Chunky;

class Chunk
{
    protected static $valid = [
        'date' => 'setDate',
        'tag-string' => 'setTagByString',
        'timestamp' => 'setTimestamp'
    ];
    
    protected $chunk;
    
    protected $tags = [];
    
    /**
     * @var string Timestamp
     */
    protected $timestamp;
    
    /**
     * Chunk constructor.
     */
    public function __construct()
    {
    }
    
    public function getChunk()
    {
        return $this->chunk;
    }
    
    public function setChunk($chunk)
    {
        $this->chunk = $chunk;
        
        return $this;
    }
    
    public function setTagByString($string)
    {
        foreach (explode('|', $string) as $tagKeyValueUnParsed) {
            $keyValue = explode(':', $tagKeyValueUnParsed, 2);
            
            if (count($keyValue) != 2) {
                throw new \Exception("Error parsing tags: $string");
            }
            
            $this->setTag(...$keyValue);
        }
        
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
    
    public function set($data)
    {
        foreach ($data as $key => $val) {
            if (isset(static::$valid[$key])) {
                $method = static::$valid[$key];
                
                $this->$method($val);
            }
        }
        
        return $this;
    }
    
    public function tagsToString()
    {
        $tagData = [];
        
        foreach ($this->tags as $key => $value) {
            $tagData[] = "$key:$value";
        }
        
        return implode('|', $tagData);
    }
    
    public function toArray()
    {
        return [
            $this->chunk,
            $this->tagsToString()
        ];
    }
    
    public function isTagEqual($tag, $equalTo)
    {
        $tagValue = $this->getTag($tag);
        
        if ($tagValue === false ){
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
    
    protected function emitTagInfo($tag, $value)
    {
    }
}