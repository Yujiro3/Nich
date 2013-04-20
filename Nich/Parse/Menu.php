<?php
/**
 * Menu関数
 *
 * @package         Nich
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 * @filesource
 */

/**
 * メニューの解析
 *
 * @access public
 * @param string $html
 * @return array カテゴリリスト
 */
function parseMenu($html) {
    $list   = explode("\n", $html);
    $result = array();
    $row    = null;

    foreach ($list as $line) {
        if (preg_match('/<BR><BR><B>(.+?)<\/B><BR>/', $line, $match)) {
            if ($match[1] != 'ツール類' && $match[1] != '他のサイト') {
                /**
                 * カテゴリー名抽出処理
                 */
                $row = is_null($row) ? 0:$row += 1;
                $result[$row] = array(
                        'category'=> $match[1],
                        'host'=>     '2ch.net',
                        'threads'=>  array()
                    );
            }
        } elseif (preg_match('/<A HREF=http:\/\/(.+?)\.2ch\.net\/(.+?)\/>(.+?)<\/A>/', $line, $match) && $row !== null) {
            /**
             * 板URL抽出処理
             */
            $result[$row]['threads'][] = array(
                    'server'=> $match[1],        // サーバ
                    'board'=>  $match[2],        // 板
                    'name'=>   $match[3],        // 名前
                );
        } elseif (preg_match('/<A HREF=http:\/\/(.+?)\.bbspink\.com\/(.+?)\/>(.+?)<\/A>/', $line, $match)) {
            /**
             * BBSPINK板URL抽出処理
             */
            $result[$row]['threads'][] = array(
                    'server'=> $match[1],        // サーバ
                    'board'=>  $match[2],        // 板
                    'name'=>   $match[3],        // 名前
                );
            $result[$row]['host'] = 'bbspink.com';
        } elseif (preg_match('/<A HREF=http:\/\/(.+?)\.machi\.to\/(.+?)\/ TARGET=_blank>(.+?)<\/A>/', $line, $match)) {
            /**
             * まちＢＢＳ板URL抽出処理
             */
            $result[$row]['threads'][] = array(
                    'server'=> $match[1],        // サーバ
                    'board'=>  $match[2],        // 板
                    'name'=>   $match[3],        // 名前
                );
            $result[$row]['host'] = 'machi.to';
        }
    } // foreach ($lines as $line)

    return $result;
}

/**
 * メニューの取得とスレッドデータ保存
 *
 * @access public
 * @param string $url        メニューURL
 * @param array  $categories カテゴリリスト
 * @param string $path       スレッド保存パス
 * @return boolean
 */
return function ($url, $categories, $path) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL,            $url);
    curl_setopt($curl, CURLOPT_USERAGENT,      'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0) Gecko/20100101 Firefox/8.0');
    curl_setopt($curl, CURLOPT_ENCODING ,      'gzip,deflate');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 4);
    $html = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);
    
    if (200 != $code) {
        return false;
    }
    $html = mb_convert_encoding($html, 'UTF-8', 'SJIS-WIN');

    /* 利用カテゴリのチェック用配列 */
    foreach ($categories as $key=> $label) {
        $wa2en[$label] = $key;
    }

    $result = parseMenu($html);

    foreach ($result as $row) {
        if (!empty($wa2en[$row['category']])) {
            $buff = "<?php\n".
                    "/**\n".
                    " * Nichanスレッドリスト\n".
                    " *\n".
                    " * @package         Nichan\n".
                    " * @author          Yujiro Takahashi <yujiro3@gamil.com>\n".
                    " */\n".
                    "return array(\n";

            foreach ($row['threads'] as $thread) {
                $buff .= "    array (\n".
                         "        'url'   => '{$thread['server']}.{$row['host']}',\n".
                         "        'board' => '{$thread['board']}',\n".
                         "        'name'  => '{$thread['name']}',\n".
                         "    ),\n";
            }
            $buff .= ");\n";
        
            file_put_contents($path.$wa2en[$row['category']].'.php', $buff);
        }
    }
    return true;
};

