Front Matter
============

[![Packagist](https://img.shields.io/packagist/v/webuni/front-matter.svg?style=flat-square)](https://packagist.org/packages/webuni/front-matter)
[![Build Status](https://img.shields.io/github/workflow/status/webuni/front-matter/Tests/master.svg?style=flat-square)](https://github.com/webuni/front-matter/actions?query=workflow%3ATests+branch%3Amaster)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/webuni/front-matter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/webuni/front-matter/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/webuni/front-matter/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/webuni/front-matter/?branch=master)

Front matter (yaml, json, neon, toml) parser and dumper for PHP. Front matter allows page-specific variables
to be included at the top of a page.

Installation
------------

This project can be installed via Composer:

    composer require webuni/front-matter

Usage
-----

Parse an arbitrary `string`
===========================

```php
<?php

$frontMatter = new Webuni\FrontMatter\FrontMatter();

$document = $frontMatter->parse($str);

$data = $document->getData();
$content = $document->getContent();
```

Test if a `string` has front matter
====================================================

```php
<?php

$frontMatter = new Webuni\FrontMatter\FrontMatter();

$hasFrontMatter = $frontMatter->hasFrontMatter($str);
```

Alternatives
------------

- https://github.com/spatie/yaml-front-matter
- https://github.com/mnapoli/FrontYAML
- https://github.com/Modularr/YAML-FrontMatter
- https://github.com/vkbansal/FrontMatter
- https://github.com/kzykhys/YamlFrontMatter
- https://github.com/orchestral/kurenai
