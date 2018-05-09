<?
function new_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    //$url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<li><a href="'.$url.'1'.$add.'"> |< </a></li>'.PHP_EOL; //처음
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<li><a href="'.$url.($start_page-1).$add.'"> < </a></li>'.PHP_EOL; //이전

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<li><a href="'.$url.$k.$add.'">'.$k.'</a></li>'.PHP_EOL; //페이지
            else
                $str .= '<li class="active"><a href="'.$url.$k.$add.'">'.$k.'</a></li>'.PHP_EOL; //현재 페이지
        }
    }

    if ($total_page > $end_page) $str .= '<li><a href="'.$url.($end_page+1).$add.'"> > </a></li>'.PHP_EOL; //다음

    if ($cur_page < $total_page) {
        $str .= '<li><a href="'.$url.$total_page.$add.'"> >| </a></li>'.PHP_EOL; //맨끝
    }

    if ($str)
        return "<ul class=\"pagination\">{$str}</ul>";
    else
        return "";
}
?>