<?php

function http_get_body(string $url, array $query = []): string
{
    $qs = http_build_query($query);
    if ($qs) {
        $url .= (str_contains($url, '?') ? '&' : '?') . $qs;
    }

    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    // Si querés igual que antes (verify=false):
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

    $body = curl_exec($ch);

    if ($body === false) {
        $err = curl_error($ch);
        curl_close($ch);
        throw new Exception($err);
    }

    $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status < 200 || $status >= 300) {
        throw new Exception("HTTP $status: $body", $status);
    }

    return $body;
}