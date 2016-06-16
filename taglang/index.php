<?php
include_once "lib/Translate.class.php";
$t  = new Translate(true);
echo "<br>";
$_t = new Translate(true, "app/locale/translate/", "en_US");
echo $t->__gettext("label.name");
echo "<br>";
echo $_t->__gettext("label.name");



$t  = new Translate(false,"app/locale/translate/","pt_BR");
echo "<p/>";
echo "pt_BR  :". $t->__gettext("label.name");

$t  = new Translate(false,"app/locale/translate/","en_US");
echo "<br>";
echo "en_US  :".$t->__gettext("label.name");

?>