<?php

if(!defined('_JEXEC'))
{
    define('_JEXEC', 1);
    define('DS', DIRECTORY_SEPARATOR);
    if (file_exists(dirname(__FILE__) . '/defines.php'))
    {
        include_once dirname(__FILE__) . '/defines.php';
    }
    if (!defined('_JDEFINES'))
    {
        define('JPATH_BASE', $_SERVER['DOCUMENT_ROOT']);
        require_once JPATH_BASE.'/includes/defines.php';
    }
    require_once JPATH_BASE.'/includes/framework.php';
    $app = JFactory::getApplication('site');
    $app->initialise();
}

$db = JFactory::getDBO();

$email = trim($_POST['email']);
if (filter_var($email, FILTER_VALIDATE_EMAIL))
{
    try
    {
        $db->setQuery("INSERT INTO #__z_emails (email) values(" . $db->quote($email) . ")");
        $db->execute();
    }
    catch (Exception $e)
    {

    }

    $book_id = "235361";
    $client_id = "c31f63553776ae65c0e7ad656f745139";
    $secret = "ab524a61b022280677583719a16f77a8";

    // --- получим токен:
    $data = Array(
        "grant_type"    => "client_credentials",
        "client_id"     => $client_id,
        "client_secret" => $secret,
    );
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, 'https://api.sendpulse.com/oauth/access_token');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $out = json_decode(curl_exec($curl));

    $token = trim($out->access_token);

    // @file_put_contents($_SERVER['DOCUMENT_ROOT'].'/'.time(), $token);

    unset($data);
    // --- отправим данные:
    $emails = Array(
        "emails" => serialize( [ $email ] ),
        "confirmation" => "force",
        "sender_email" => "mary.chugueva@piano.by",
    );

    curl_setopt($curl, CURLOPT_URL, 'https://api.sendpulse.com/addressbooks/'.$book_id.'/emails');
    curl_setopt($curl, CURLOPT_HTTPHEADER,
        array(
            "Accept: application/json",
            "Authorization: Bearer ".$token,
        )
    );

    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $emails);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    $out = json_decode(curl_exec($curl));
    curl_close($curl);

    echo "1";
}
else
    echo "0";
