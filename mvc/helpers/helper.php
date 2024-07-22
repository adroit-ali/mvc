<?php
function relative_get_url($args){
    $queryString = $_SERVER['QUERY_STRING'];
    parse_str($queryString, $queryParams);
    foreach ($args as $key => $value) {
        $queryParams[$key] = $value;
    }
    $newQueryString = http_build_query($queryParams);
    $baseUrl = strtok($_SERVER["REQUEST_URI"], '?');
    return $baseUrl . '?' . $newQueryString;

}
