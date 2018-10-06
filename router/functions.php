<?php
function IS_HTTPS()
{
    return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off';
}

function GET_PATH_INFO($baseUrl = null)
{
    static $pathInfo;

    if (!$pathInfo) {
        $pathInfo = $_SERVER['REQUEST_URI'];

        if (!$pathInfo) {
            $pathInfo = '/';
        }

        $schemeAndHttpHost = IS_HTTPS() ? 'https://' : 'http://';
        $schemeAndHttpHost .= $_SERVER['HTTP_HOST'];

        if (strpos($pathInfo, $schemeAndHttpHost) === 0) {
            $pathInfo = substr($pathInfo, strlen($schemeAndHttpHost));
        }

        if ($pos = strpos($pathInfo, '?')) {
            $pathInfo = substr($pathInfo, 0, $pos);
        }

        if (null != $baseUrl) {
            $pathInfo = substr($pathInfo, strlen($pathInfo));
        }

        if (!$pathInfo) {
            $pathInfo = '/';
        }
    }

    return $pathInfo;
}
?>