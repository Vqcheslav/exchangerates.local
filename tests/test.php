<?php


// $timeOneMonthAgo = strtotime('-1 month');
// $now             = new \DateTime(date('d-m-Y h:i:s', $timeOneMonthAgo), new \DateTimeZone("Europe/Moscow"));
// $step            = new \DateInterval('P1D');
// $period          = new \DatePeriod($now, $step, date('d', time() - $timeOneMonthAgo) - 1);

// foreach($period as $dateTime) {
//    /*  $rawXml = file_get_contents('http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $dateTime->format('d/m/Y'));
//     $xml    = new \SimpleXMLElement($rawXml);

//     foreach ($xml->Valute as $valute) {
//         echo $valute->attributes() . '   ';
//         echo $valute->Name;
//     } */
//     echo strtotime($dateTime->format('m/d/Y'));

//     break;
// }

// echo mktime(0, 0, 0, 6, 11, 2022) . '   ';
// echo strtotime('06/11/2022') . '   ';
// echo mktime(0, 0, 0, explode('/', '11/06/2022')[1], explode('/', '11/06/2022')[0], explode('/', '11/06/2022')[2]);


// $dateMonthAgo = date('d/m/Y', strtotime('-1 month'));
// $now          = date('d/m/Y');
// // 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $now
// $dailyRawXml = file_get_contents('dailyXml.xml');
// $dailyXml    = new \SimpleXMLElement($dailyRawXml);

// foreach ($dailyXml->Valute as $valute) {
//     $dynamicRawXml = file_get_contents('dynamicXml.xml');
//         //sprintf('http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=%s&date_req2=%s&VAL_NM_RQ=%s', $dateMonthAgo, $now, $valute->attributes())
    
//     $dynamicXml = new \SimpleXMLElement($dynamicRawXml);

//     // var_dump($valute->attributes());
    
//     // exit;

//     foreach ($dynamicXml->Record as $record) {
//         $date = $record->attributes()['Date'];
//         $value = $record->Value;
//         // echo $valute->attributes() . ' ' . $valute->NumCode . ' ' . $valute->CharCode . ' ' . iconv('UTF-8', 'Windows-1251', $valute->Name) . ' ' . $value . ' ' . strtotime($date)  . '
//         //  ';


//     }
// }

// echo $date = (new \DateTime())->format('d/m/Y');
// echo $date2 = (new \DateTime('-1 month'))->format('d/m/Y');



//echo sprintf('http://www.cbr.ru/scripts/XML_dynamic.asp?date_req1=%s&date_req2=%s&VAL_NM_RQ=%s', '11/06/2022', '11/07/2022', 'R01010');



// echo /* iconv('UTF-8', 'Windows-1251',  */'1 РђРІСЃС‚СЂР°Р»РёР№СЃРєРёР№ РґРѕР»Р»Р°СЂ'/* ) */;

// echo strtotime('19.06.2022');
/* $date = '19/06/2022';

$dategg = explode('/', $date);

echo $timestamp = mktime(0, 0, 0, $dategg[1], $dategg[0], $dategg[2]) . '   ';
echo (new \DateTime($date))->format('d-m-Y'); */
