<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>簡易投票システム</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <div class="page-header">
    <h1>簡易投票システム</h1>
  </div>

  


<h1>テスト記事2</h1>
<div class="entry-body">
<p>我が輩は猫でアル</p>

<p>
    <a class="vote" data-voteId="580" data-voteRectId="rect-580" data-voteToken="e552f424f8dd0b684f8e0157e339e6d893946a0ee552f424f8d">Like</a>
    <span id="rect-580"></span>
</p>
</div>

<h1>テスト記事</h1>
<div class="entry-body">
<p>テストですよ。いいね！と思ったらぽちってね。</p>

<p>
    <a class="vote" data-voteId="579" data-voteRectId="rect-579" data-voteToken="2bc631ba0f822db4f07fef0dc5b9d500293f9eea2bc631ba0f8">Like</a>
    <span id="rect-579"></span>
</p>
</div>


<script src="http://works.bit-part.net/i-pairs/vote/simpleVote.js"></script>
<script>
(function($){
    $('a.vote').simpleVote({
        url: 'http://works.bit-part.net/i-pairs/vote/simpleVote.php',
        cookieDays: 30,
        cookiePath: '/i-pairs/vote',
        notVotedText: 'まだ投票されていません'
    });
})(jQuery);
</script>



</div>

</body>
</html>