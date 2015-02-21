# simpleVote

## Overview

This is a simple voting system. Users don't need to sign in. If you need strict voting count, we don't recommend to use this simpleVote.

## Usage

Set files to your server to be the following structure:

    ├── data
    ├── js
    │   └── simpleVote.js
    ├── php
    │   └── simpleVote.php
    └── sample.html

Include jQuery and simpleVote.js on your webpage, and excute simpleVote() the following:

    <span>
      <a href="#" class="vote" data-voteId="1" data-voteRectId="rect-1" data-voteToken="e552f424f8dd0b684f8e0157e339e6d893946a0ee552f424f8d">Like</a>
      <span id="rect-1"></span>
    </span>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="js/simpleVote.js"></script>
    <script>
    jQuery(function(){
      $('a.vote').simpleVote({
        url: '/php/simpleVote.php', // Path to simpleVote.php
        cookieDays: 30, // Cookie's expiration
        cookiePath: '/', // Cookie's effective path
        notVotedText: 'Nobody voted.'
      });
    });
    </script>

* You don't need to use the a element.
* You can use your favorite class name.
* The data-voteId attribute is unique.
* The data-voteRectId attribute is same value as the id attribute of the element for showing the voting count.
* The data-voteToken attribute is a random 51 characters.
