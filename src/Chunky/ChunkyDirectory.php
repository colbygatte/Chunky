<?php

namespace ColbyGatte\Chunky;

use DirectoryIterator;
use Exception;

/**
 * Class Trackr
 *
 * @package ColbyGatte\Trackr
 */
abstract class ChunkyDirectory
{
    /**
     * @var \ColbyGatte\Chunky\Chunks
     */
    protected $chunks;
    
    /**
     * Location of the directory.
     *
     * @return string
     */
    abstract function directoryLocation();
    
    /**
     * @param string $append
     *
     * @return string
     */
    function getPath($append = '')
    {
        return $this->directoryLocation().'/'.$append;
    }
    
    /**
     *
     */
    public function loadAllChunks()
    {
        $chunks = $this->newChunks();
        
        foreach ($this->getDirectoryIterator() as $fileInfo) {
            if ($timestampFromFilename = $this->parseLogFileInfo($fileInfo)) {
                $this->loadLog($chunks, $timestampFromFilename);
            }
        }
        
        return $chunks;
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
     * @return \ColbyGatte\Chunky\Chunks
     */
    public function getChunks()
    {
        return $this->chunks;
    }
    
    /**
     * @return \ColbyGatte\Chunky\Chunks
     */
    public function newChunks()
    {
        return new Chunks($this);
    }
    
    /**
     * @param null $timestamp
     *
     * @return \ColbyGatte\Chunky\Chunks
     */
    public function newLogFile($timestamp = null)
    {
        $timestamp = $timestamp ?: time();
        
        $fh = fopen($this->getPath($timestamp.'.csv'), 'w');
        
        fputcsv($fh, ['chunk', 'tags']);
        
        return $this->newChunks()->setFileHandle($fh);
    }
    
    /**
     * @param \ColbyGatte\Chunky\Chunks $chunks
     * @param $trackrLogTimestamp
     *
     * @return \ColbyGatte\Chunky\Chunks
     * @throws \Exception
     */
    public function loadLog(Chunks $chunks, $trackrLogTimestamp)
    {
        $file = $this->getPath($trackrLogTimestamp.'.csv');
        
        if (! file_exists($file)) {
            throw new Exception("Trying to load a Trackr log that does not exist: $trackrLogTimestamp");
        }
        
        $csvFileHandle = fopen($file, 'r');
        
        while (($row = fgetcsv($csvFileHandle)) !== false) {
            if (count($row) < 2) {
                continue;
            }
            
            $chunks->addNewChunk([
                'chunk' => $row[0],
                'timestamp' => $trackrLogTimestamp,
                'tag-string' => $row[1]
            ]);
        }
        
        return $chunks;
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