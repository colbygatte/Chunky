# Chunky
Tiny framework for keeping track of "chunks" (strings of text) and tagging them with data.

`composer require colbygatte/chunky`

### Vocab
#### Notebook
A Notebook is a directory. 
Each notebook has csv files, where each file is a "page" in the notebook. Each filename is a timestamp.

#### Page
A Page is a group of tagged chunks that are timestamped (using the filename).
 
#### Entry
An Entry is a chunk and it's tags.

#### Search
The search class is used to filter entries in a Page.

#### Constraint
Constraints are what the Search class uses for filtering.

#### Search Report
When using Search, each entry keeps track keeps track of what constraints were passed or failed.

## How to use
To get started, you must extend the Notebook class and implement directoryLocation() function, returning a filepath to the directory you want to use as a notebook.

*To be continue*
