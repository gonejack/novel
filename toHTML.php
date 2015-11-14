<?php
/**
 * Created by PhpStorm.
 * User: Youi
 * Date: 2015-11-14
 * Time: 16:08
 */

function section_note_by_head_regex($note_str, $header_regex) {
    $chapters = [];

    if (preg_match_all("{$header_regex}m", $note_str, $chapter_heads, PREG_OFFSET_CAPTURE)) {
        $chapter_heads = $chapter_heads[0];
        $str_end       = mb_strlen($note_str);
        $left          = 0;
        $right         = current($chapter_heads)[1];
        do {
            $len = $right - $left;
            $len > 0 && $chapters[] = mb_substr($note_str, $left, $len);
            $left = $right;
        } while (next($chapter_heads) === false ? $right < $str_end && $right = $str_end : $right = current($chapter_heads)[1]);
    } else $chapters[] = $note_str;

    return $chapters;
}

function parse_out_lines($str) {
    $arr = explode("\n", $str);
    $brr = [];
    foreach ($arr as $line) {
        $line = trim($line);
        strlen($line) && $brr[] = $line;
    }

    return $brr;
}

function to_h3_and_p_tag($lines_arr) {
    $lines_arr[0] = "<h3>$lines_arr[0]</h3>";
    for ($i = count($lines_arr); --$i > 0;) {
        $lines_arr[$i] = "<p>$lines_arr[$i]</p>";
    }

    return $lines_arr;
}

$note_str = file_get_contents('the_red_and_the_black.txt');
$chapters_arr = section_note_by_head_regex($note_str, '/^第.*$/');
foreach ($chapters_arr as & $chapter) {
    $chapter_arr = parse_out_lines($chapter);
    $chapter_arr = to_h3_and_p_tag($chapter_arr);
    $chapter = implode("\n", $chapter_arr);
}
file_put_contents('final.txt', implode("\n", $chapters_arr));
var_dump($chapters_arr);