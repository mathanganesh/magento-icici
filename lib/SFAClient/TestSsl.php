<?php

include("Sfa/BillToAddress.php");
include("Sfa/CardInfo.php");
include("Sfa/Merchant.php");
include("Sfa/MPIData.php");
include("Sfa/ShipToAddress.php");
include("Sfa/PGResponse.php");
include("Sfa/PostLibPHP.php");
include("Sfa/PGReserveData.php");

$oMPI 			= 	new MPIData();
$oCI			=	new	CardInfo();
$oPostLibphp	=	new	PostLibPHP();
$oMerchant		=	new	Merchant();
$oBTA			=	new	BillToAddress();
$oSTA			=	new	ShipToAddress();
$oPGResp		=	new	PGResponse();
$oPGReserveData =	new PGReserveData();

$oMerchant->setMerchantDetails("96013917","96013917","96013917","142.4.10.91",rand()."","Ord1234","http://cybrainsoftware.com/myshop/SFAClient/SFAResponse.php","POST","INR","INV123","req.Sale","100","","Ext1","true","Ext3","Ext4","New PHP");

$oBTA->setAddressDetails ("CID","Tester","Aline1","Aline2","Aline3","Pune","A.P","48927489","IND","tester@soft.com");

$oSTA->setAddressDetails ("Add1","Add2","Add3","City","State","443543","IND","sad@df.com");

#$oMPI->setMPIRequestDetails("1245","12.45","356","2","2 shirts","12","20011212","12","0","","image/gif, image/x-xbitmap, image/jpeg, image/pjpeg, application/vnd.ms-powerpoint, application/vnd.ms-excel, application/msword, application/x-shockwave-flash, */*","Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)");

$oPGResp=$oPostLibphp->postSSL($oBTA,$oSTA,$oMerchant,$oMPI,$oPGReserveData);


if($oPGResp->getRespCode() == '000'){
	$url	=$oPGResp->getRedirectionUrl();
	#$url =~ s/http/https/;
	#print "Location: ".$url."\n\n";
	#header("Location: ".$url);
	redirect($url);
}else{
	print "Error Occured.<br>";
	print "Error Code:".$oPGResp->getRespCode()."<br>";
	print "Error Message:".$oPGResp->getRespMessage()."<br>";
}

# This will remove all white space
#$oResp =~ s/\s*//g;

# $oPGResp->getResponse($oResp);

#print $oPGResp->getRespCode()."<br>";

#print $oPGResp->getRespMessage()."<br>";

#print $oPGResp->getTxnId()."<br>";

#print $oPGResp->getEpgTxnId()."<br>";

function redirect($url) {
	if(headers_sent()){
	?>
		<html><head>
			<script language="javascript" type="text/javascript">
				window.self.location='<?php print($url);?>';
			</script>
		</head></html>
	<?php
		exit;
	} else {
		header("Location: ".$url);
		exit;
	}
}
?>