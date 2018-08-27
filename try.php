<?php
/**
 * Created in Heliard.
 * User: gvammer gvammer@rambler.ru
 * Date: 26.08.2018
 * Time: 21:15
 */

include dirname(__FILE__) . '/SeldonApi.php';

$r = new \Opengift\Api\SeldonApi();
echo($r->auth());
