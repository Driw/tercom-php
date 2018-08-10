<?php


function IncludeThirdParty($thirdParty)
{
	include sprintf('%s/src/3rdparty/%s/include.php', $_SERVER['DOCUMENT_ROOT'], $thirdParty);
}

?>