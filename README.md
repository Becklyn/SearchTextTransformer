Search Text Transformer
=======================

Transforms HTML to searchable plain text for usage in conjunction with a search engine (like Elasticsearch).


Installation
------------

Install via composer.


Usage
-----

```php
<?php

use Becklyn\SearchText\SearchTextTransformer;

$transformer = new SearchTextTransformer();
$plain = $transformer->transform("<p>Some HTML content</p>");
```


Testing
-------

All test cases belong into `tests/fixtures` and must have the file extension `.test`.

The test format is:

```
--TEST--
Here is a plain text description of this test.
--HTML--
<p>Some html.</p>
--EXPECT--
The expected result.
```

The `--TEST--` segment is optional.
