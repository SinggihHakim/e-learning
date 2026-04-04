<?php
function getYoutubeIf($url) {
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?|shorts)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $url, $match);
    if (isset($match[1])) {
        return 'https://www.youtube.com/embed/' . $match[1];
    }
    return $url;
}

echo getYoutubeIf("https://www.youtube.com/watch?v=dQw4w9WgXcQ") . "\n";
echo getYoutubeIf("https://youtu.be/dQw4w9WgXcQ?si=abcdef") . "\n";
echo getYoutubeIf("https://www.youtube.com/shorts/p4u3lR9gAio") . "\n";
