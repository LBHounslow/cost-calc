<?php

function flash($message)
{
    session()->flash('flash_message', $message);
}

function slack($errstr)
{

    $date = '01/01/1980';
    $application = 'madm';
    $errno = '0';
    //$errstr = '';
    $errfile = '0';
    $errline = '0';


    $channel = 'error-log-dev';
    $username = 'error-monster';
    $url = 'https://hooks.slack.com/services/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';
    $icon_emoji = ':ghost:';
    $text = "```[date] => {$date}\n[application] => {$application}\n[errno] => {$errno}\n[errstr] => {$errstr}\n[errfile] => {$errfile}\n[errline] => {$errline}```";

    $data = json_encode(array(
        "channel" => "#{$channel}",
        "username" => $username,
        "text" => $text,
        "icon_emoji" => $icon_emoji
    ));

    $ch = curl_init("{$url}");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, array('payload' => $data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    //echo date('Y-m-d H:i:s') . " slack says => " . $result . "\n";
    curl_close($ch);
}