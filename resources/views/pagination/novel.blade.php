<?php
// config
$link_limit = 7; // maximum number of links (a little bit inaccurate, but will be ok for now)
?>

@if ($paginator->lastPage() > 1)
    <ul class="pg-ul">
        <li>分页 »</li>
        @if($paginator->currentPage() > 1)
            <li>
                <a href="{{ $paginator->url($paginator->currentPage()-1) }}"><</a>
            </li>
        @endif
        <li>
            <a class="{{ ($paginator->currentPage() == 1) ? ' active' : '' }}" href="{{ $paginator->url(1) }}">1</a>
        </li>
            <?php
                $half_total_links = floor($link_limit / 2);
                $from = $paginator->currentPage() - $half_total_links;
                $to = $paginator->currentPage() + $half_total_links;
                if ($paginator->currentPage() < $half_total_links) :
                    $to += $half_total_links - $paginator->currentPage();
                elseif($paginator->currentPage() > $half_total_links+1) :
            ?>
                <li>
                    <a  href="javascript:;">...</a>
                </li>
            <?php
                endif;
                if ($paginator->lastPage() - $paginator->currentPage() < $half_total_links) {
                    $from -= $half_total_links - ($paginator->lastPage() - $paginator->currentPage()) - 1;
                }
            ?>
        @for ($i = 2; $i < $paginator->lastPage(); $i++)
            @if ($from < $i && $i < $to)
                <li>
                    <a class="{{ ($paginator->currentPage() == $i) ? ' active' : '' }}" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                </li>
            @endif
        @endfor
        @if($paginator->lastPage() - $paginator->currentPage() > $half_total_links)
            <li>
                <a  href="javascript:;">...</a>
            </li>
        @endif
        <li>
            <a class="{{ ($paginator->currentPage() == $paginator->lastPage()) ? ' active' : '' }}" href="{{ $paginator->url($paginator->lastPage()) }}">{{ $paginator->lastPage() }}</a>
        </li>
        @if($paginator->currentPage() < $paginator->lastPage())
            <li>
                <a href="{{ $paginator->url($paginator->currentPage()+1) }}">></a>
            </li>
        @endif
    </ul>
@endif