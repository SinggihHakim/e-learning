<?php
function getYoutubeIf($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match);
    if (isset($match[1])) {
        return 'https://www.youtube.com/embed/' . $match[1];
    }
    return $url;
}

echo getYoutubeIf("https://youtu.be/PJyurFh65PY?si=xHj6U8wVsTu9COYz") . "\n";
