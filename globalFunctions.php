<?php


function IncludeThirdParty($thirdParty)
{
	include sprintf('%s/src/3rdparty/%s/include.php', __DIR__, $thirdParty);
}

?>