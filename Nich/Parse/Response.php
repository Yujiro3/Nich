<?php
/**
 * レスポンス整形
 *
 * @package         Nich
 * @author          Yujiro Takahashi <yujiro3@gamil.com>
 * @filesource
 */

/**
 * レス付きソート
 *
 * @access public
 * @param array  $list 板データ
 * @return array ソート済みデータ
 */
return function ($list) {
    $result = $walk = $parents = $matches = array();

    /**
     * 配列の成形
     */
    foreach ($list as $key=> $row) {
        $posted = explode(' ', $row[2]);

        $list[$key] = array(
            'id'   => $posted[2],
            'no'   => ($key + 1),
            'date' => $posted[0],
            'time' => $posted[1],
            'be'   => $posted[3],
            'name' => $row[0],
            'mail' => $row[1],
            'body' => $row[3]
        );
        $walk[$key] = array($key=> $key);
    }

    /**
     * レスの取得
     */
    foreach ($list as $key=> $row) {
        if (preg_match('/>&gt;&gt;([0-9]+)</i', $row['body'], $matches)) {
            $pos = $matches[1] - 1;
            if ($pos != $key) {
                $walk[$pos][$key] = $key;
                $parents[$key]    = $pos;
            }
        }
    }

    /**
     * 表示順に配列を分布
     */
    arsort($parents);
    foreach ($parents as $key=> $parent) {
        if (count($walk[$key]) > 1) {
            $walk[$parent][$key] = $walk[$key];
        }
        unset($walk[$key]);
    }

    /**
     * １次元配列に成形
     */    
    array_walk_recursive($walk, function ($value, $key) use (&$list, &$result) {
        $result[] = $list[$value];
    });

    return $result;
};
