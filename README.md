2ch Library (PHP5.3)
======================
2ch データの取得ライブラリ。

利用方法
------

### コンストラクタ ###
```php
include 'Nich/Nich.php';
$nich = new Nich();
```    


### カテゴリリストの取得 ###
```php
$list = $nich->getCategories();
print_r($list);
```    


    Array
    (
        [quake]  => 地震,
        [pickup] => おすすめ,
        [be]     => be,
    

### スレッドリストの取得 ###
```php
$nich->thread = 'be';    // カテゴリリストのキーを設定する
$list = $nich->getThreads();
print_r($list);
```    


    Array
    (
        [0] => Array
            (
                [url]   => kohada.2ch.net
                [board] => be
                [name]  => 面白ネタnews
            )
    

### スレッドタイトルの取得 ###
```php
$nich->host  = 'kohada.2ch.net';
$nich->board = 'be';
$list = $nich->getSubjects();
print_r($list);
```    


    Array
    (
        [1344847411] => Array
            (
                [subject] => 安価で元カノにメールする
                [res] => 49
            )
    


### スレッド内容の取得 ###
```php
$nich->host  = 'kohada.2ch.net';
$nich->board = 'be';
$nich->id    = '1344847411';    // スレッドタイトルのキーを設定する

$list = $nich->getBoard();
print_r($list);
```    


    Array
    (
        [0] => Array
            (
                [0] => 名無しさん＠ログイン中
                [1] => sage
                [2] => 2012/08/13(月) 17:43:31.16 ID:26BneCgp BE:3395688858-2BP(0)
                [3] =>  暇だから付き合え
                [4] => 安価で元カノにメールする
            )
        [1] => Array
            (
                [0] => 名無しさん＠ログイン中
                [1] => sage
                [2] => 2012/08/13(月) 17:46:44.70 ID:26BneCgp BE:3056120249-2BP(0)
                [3] =>  とりあえずスペック <br> ＞＞1 <br> 男 <br> 18 <br> 元カノ <br> 18 <br> ちっちゃくて可愛い <br> ロリ <br> 胸は人並み <br> 二次好き <br>  <br> こんくらいでいいか
                [4] =>
            )
    

### スレッド内容の取得（ソート済み） ###
```php
$nich->host  = 'kohada.2ch.net';
$nich->board = 'be';
$nich->id    = '1344518750';    // スレッドタイトルのキーを設定する

$list = $nich->getThread();
print_r($list);
```    


    Array
    (
        [0] => Array
            (
                [id] => ID:26BneCgp
                [no] => 1
                [date] => 2012/08/13(月)
                [time] => 17:43:31.16
                [be] => BE:3395688858-2BP(0)
                [name] => 名無しさん＠ログイン中
                [mail] => sage
                [body] =>  暇だから付き合え
            )
        [1] => Array
            (
                [id] => ID:9um7i1JO
                [no] => 11
                [date] => 2012/08/13(月)
                [time] => 18:10:57.15
                [be] => BE:4782523878-2BP(0)
                [name] => 名無しさん＠ログイン中
                [mail] =>
                [body] =>  <a href="../test/read.cgi/be/1344847411/1" target="_blank">&gt;&gt;1</a>は現在、元カノさんのことどう思ってるの？ <br> 未練とかあるの？
            )
        [2] => Array
            (
                [id] => ID:9um7i1JO
                [no] => 40
                [date] => 2012/08/13(月)
                [time] => 18:32:40.11
                [be] => BE:5380338997-2BP(0)
                [name] => 名無しさん＠ログイン中
                [mail] =>
                [body] =>  俺たちで<a href="../test/read.cgi/be/1344847411/1" target="_blank">&gt;&gt;1</a>を復縁させるか…
            )
    


ライセンス
----------
Copyright &copy; 2012 Yujiro Takahashi  
Licensed under the [MIT License][MIT].  
Distributed under the [MIT License][MIT].  

[MIT]: http://www.opensource.org/licenses/mit-license.php
