# simpleVote

## Overview

This is a simple voting system. Users don't need to sign in. If you need strict voting count, we don't recommend to use this.

## Install

Make a directory at your web root or anywhere

```
mkdir simplevote
cd simplevote
```

Install simpleVote

```
composer require bitpart/simplevote
```

Move sample files to the directory

```
cp vendor/bitpart/simplevote/web/index.php index.php
cp vendor/bitpart/simplevote/web/sample.html sample.html
```

## Usage

Open the `sample.html` on your browser and click like buttons.
If you need to place files the different structure above, please modify the path of `require_once` on `index.php`
