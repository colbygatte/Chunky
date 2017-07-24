<?php

namespace ColbyGatte\Chunky;

use Countable;
use Exception;

/**
 * Page represents a single file in a directory, where the directory is a Notebook
 *
 * This class is countable: The count will return the number of unique chunks.
 *
 * @package ColbyGatte\Page
 */
class Page implements Countable
{
    use GetHelper;
    
    /**
     * @var \ColbyGatte\Chunky\Notebook
     */
    protected $notebook;
    
    /**
     * Associative array:
     *   Key: Chunk
     *   Value: Array of entries
     *
     * @var array
     */
    protected $entries = [];
    
    /**
     * Timestamp of the
     *
     * @var int
     */
    protected $timestamp;
    
    /**
     * @var resource
     */
    protected $fileHandle;
    
    /**
     * Lock the timestamp.
     *
     * @var bool
     */
    protected $locked = false;
    
    /**
     * Stays null until @see Page::addNote()
     * or @see Page::loadNotes() is called.
     *
     * @var string[]
     */
    protected $notes;
    
    /**
     * @param \ColbyGatte\Chunky\Notebook $notebook
     * @param int|null $timestamp
     */
    public function __construct(Notebook $notebook, $timestamp = null)
    {
        $this->notebook = $notebook;
        
        if ($timestamp !== false) {
            $this->timestamp = $timestamp ?: time();
            
            if (! file_exists($this->pagePath())) {
                touch($this->pagePath());
            }
        }
    }
    
    /**
     * Locking does not allow you to change the timestamp.
     *
     * @return $this
     */
    public function lock()
    {
        $this->locked = true;
        
        return $this;
    }
    
    /**
     * @param $timestamp
     *
     * @return $this
     * @throws \Exception
     */
    public function setTimestamp($timestamp)
    {
        if ($this->locked) {
            throw new Exception('Cannot change timestamp, Page is locked.');
        }
        
        $this->timestamp = $timestamp;
        
        return $this;
    }
    
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    
    public function loadEntries()
    {
        if (! $this->timestamp) {
            throw new Exception('Timestamp not set');
        }
        
        $file = $this->pagePath();
        
        if (! file_exists($file)) {
            throw new Exception("Trying to load a Chunky log that does not exist: {$this->timestamp}");
        }
        
        $csvFileHandle = fopen($file, 'r');
        
        while (($row = fgetcsv($csvFileHandle)) !== false) {
            if (count($row) < 2) {
                continue;
            }
            
            $this->addEntry(
                $this->makeEntry(
                    $row[0],
                    $this->getHelper()->stringToTags($row[1])
                )
            );
        }
        
        return $this;
    }
    
    /**
     * Search for all chunks equal to $chunk
     *
     * @param $chunk
     *
     * @return bool|\ColbyGatte\Chunky\Entry
     */
    public function searchForChunk($chunk)
    {
        return isset($this->entries[$chunk])
            ? $this->entries[$chunk]
            : false;
    }
    
    public function getAllUsedTags()
    {
    }
    
    /**
     * Using isset() on an associative array is faster than using in_array() on a regular array
     *
     * @return array
     */
    public function getAllChunksAsKey()
    {
        return array_fill_keys(array_keys($this->entries), true);
    }
    
    /**
     * @return \ColbyGatte\Chunky\Notebook
     */
    public function getNotebook()
    {
        return $this->notebook;
    }
    
    /**
     * @return \ColbyGatte\Chunky\Entry[]
     */
    public function getEntries()
    {
        $entries = [];
        
        foreach ($this->entries as $entriesWithSameChunk) {
            array_push($entries, ...$entriesWithSameChunk);
        }
        
        return $entries;
    }
    
    public function whereTagEqual($tag, $value = null)
    {
        $tag = is_string($tag) ? [$tag => $value] : $tag;
        
        $result = $this->notebook->newPage();
        
        foreach ($this->entries as $chunk) {
            foreach ($tag as $_tag => $_value) {
                if ($chunk->isTagEqual($_tag, $_value)) {
                    continue 2;
                }
            }
            
            $result->addEntry($chunk);
        }
        
        return $result;
    }
    
    /**
     * @param string $chunk
     * @param string|string[] $tags
     *
     * @return \ColbyGatte\Chunky\Entry
     */
    public function makeEntry($chunk, $tags)
    {
        return $this->notebook->newEntry()
            ->setChunk($chunk)
            ->setTimestamp($this->timestamp)
            ->setTag($tags);
    }
    
    /**
     * Appending is used for reading from a Page file.
     * Use the writeEntry() method when writing to a page file.
     *
     * @param \ColbyGatte\Chunky\Entry $entry
     *
     * @return $this
     */
    public function addEntry(Entry $entry)
    {
        if (! isset($this->entries[$entry->getChunk()])) {
            $this->entries[$entry->getChunk()] = [];
        }
        
        $this->entries[$entry->getChunk()][] = $entry;
        
        return $this;
    }
    
    /**
     * Check to see we have a timestamp. If we don't,
     *
     * @return $this
     * @throws \Exception
     */
    public function checkTimestamp()
    {
        if (! $this->timestamp) {
            throw new Exception('Timestamp is not set.');
        }
        
        return $this;
    }
    
    public function getFileHandle()
    {
        $this->checkTimestamp();
        
        if (! $this->fileHandle) {
            $this->fileHandle = fopen(
                $this->pagePath(),
                'a'
            );
        }
        
        return $this->fileHandle;
    }
    
    /**
     * @return string[]
     */
    public function loadNotes()
    {
        if (! file_exists($this->pageNotesPath())) {
            touch($this->pageNotesPath());
        }
        
        $fh = fopen($this->pageNotesPath(), 'r');
        
        $notes = [];
        
        while (false !== ($note = fgets($fh))) {
            $notes[] = trim($note);
        }
        
        $this->notes = $notes;
        
        return $notes;
    }
    
    public function addNote($note)
    {
        if (! is_string($note)) {
            throw new Exception('$note must be a string');
        }
        
        if (is_null($this->notes)) {
            $this->loadNotes();
        }
        
        $this->notes[] = $note;
    }
    
    public function writeNotes()
    {
        if (is_null($this->notes)) {
            throw new Exception('$notes is null');
        }
        
        $fh = fopen($this->pageNotesPath(), 'w');
        fwrite($fh, implode("\n", $this->notes));
        fclose($fh);
    }
    
    /**
     * Write entry will call @see Page::addEntry() before writing.
     *
     * @param \ColbyGatte\Chunky\Entry $entry
     *
     * @return $this
     */
    public function writeEntry(Entry $entry)
    {
        $this->addEntry($entry);
        
        fputcsv(
            $this->getFileHandle(),
            $entry->toArray()
        );
        
        return $this;
    }
    
    /**
     * Count elements of an object
     *
     * @return int Number of unique chunks
     */
    public function count()
    {
        return count($this->entries);
    }
    
    protected function pagePath()
    {
        if (! $this->timestamp) {
            throw new Exception('pagePath(): timestamp not set');
        }
        
        return $this->notebook->getPath($this->timestamp.'.csv');
    }
    
    protected function pageNotesPath()
    {
        if (! $this->timestamp) {
            throw new Exception('pagePath(): timestamp not set');
        }
        
        return $this->notebook->getPath($this->timestamp.'.notes.txt');
    }
}