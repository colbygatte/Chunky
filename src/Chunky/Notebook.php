<?php

namespace ColbyGatte\Chunky;

use DirectoryIterator;
use Exception;

/**
 * Class Trackr
 *
 * @package ColbyGatte\Trackr
 */
abstract class Notebook
{
    /**
     * @var \ColbyGatte\Chunky\Page
     */
    protected $chunks;
    
    /**
     * Location of the directory.
     *
     * @return string
     */
    abstract public function directoryLocation();
    
    /**
     * @param string $append
     *
     * @return string
     */
    public function getPath($append = '')
    {
        return $this->directoryLocation().'/'.$append;
    }
    
    /**
     *
     */
    public function loadAllEntries()
    {
        $page = $this->newPage();
        
        foreach ($this->getDirectoryIterator() as $fileInfo) {
            if ($timestampFromFilename = $this->parseLogFileInfo($fileInfo)) {
                $page->setTimestamp($timestampFromFilename)->loadEntries();
            }
        }
        
        // All entries are loaded, so lock up the page and set timestamp to null
        // so we know that this is a page of all entries.
        $page->setTimestamp(null)->lock();
        
        return $page;
    }
    
    public function getLatestTime()
    {
        $latest = 0;
        
        foreach ($this->getDirectoryIterator() as $fileInfo) {
            if ($timestamp = $this->parseLogFileInfo($fileInfo)) {
                if ($timestamp > $latest) {
                    $latest = $timestamp;
                }
            }
        }
        
        return $latest;
    }
    
    public function getLatestFile()
    {
        return $this->getPath($this->getLatestTime().'.csv');
    }
    
    /**
     * @return \ColbyGatte\Chunky\Page
     */
    public function getChunks()
    {
        return $this->chunks;
    }
    
    /**
     * A Page assigns its timestamp to itself. All we need to do is give it the Notebook it is in.
     *
     * @param int|null $timestamp
     *
     * @return \ColbyGatte\Chunky\Page
     */
    public function newPage($timestamp = null)
    {
        return new Page($this, $timestamp);
    }
    
    /**
     * The only place new Entry instances are created is here.
     * If you want to use a custom Entry, override this method.
     *
     * @return \ColbyGatte\Chunky\Entry
     */
    public function newEntry()
    {
        return new Entry;
    }
    
    /**
     * @param $timestamp
     *
     * @return \ColbyGatte\Chunky\Page
     * @throws \Exception
     */
    public function loadPage($timestamp)
    {
        return $this->newPage($timestamp)->loadEntries()->lock();
    }
    
    protected function parseLogFileInfo(\SplFileInfo $fileInfo)
    {
        $timestampFromFilename = preg_replace('/\.csv$/', '', $fileInfo->getBasename());
        
        if ($fileInfo->getExtension() == 'csv' && preg_match('/^[0-9]+$/', $timestampFromFilename)) {
            return $timestampFromFilename;
        }
        
        return false;
    }
    
    protected function getDirectoryIterator()
    {
        return new DirectoryIterator($this->directoryLocation());
    }
}