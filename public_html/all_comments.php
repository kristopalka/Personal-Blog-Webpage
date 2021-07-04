<?php
function get_page_parameters()
{
    // generate addresses
    $addresses = [];
    for ($i = 1; $i < 60; $i++) {
        if ($i < 10) $num = '0' . $i;
        else $num = '' . $i;

        $address = 'https://s0' . $num . '.labagh.pl';

        array_push($addresses, $address);
    }

    $tables = [];

    // try to download data form each page
    foreach ($addresses as $address) {
        $html = file_get_contents($address . '/index.php?page=guest_book');

        preg_match('/(\<table.*\<\/table\>)/s',$html, $result);

        //echo $result[0];
        $table['content'] = $result[0];
        $table['source'] = $address;

        $tables[$address] = $result[0];
    }

    //print_r($tables);

    return ['tables' => $tables];
}