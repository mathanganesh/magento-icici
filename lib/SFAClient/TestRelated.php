<?php



include("Sfa/Merchant.php");
include("Sfa/PGResponse.php");
include("Sfa/PostLibPHP.php");


 $oPostLibphp	=	new	PostLibPHP();

 $oMerchant	=	new	Merchant();

 $oPGResp	=	new	PGResponse();



$oMerchant->setMerchantRelatedTxnDetails("00002116","00002116","00002116","21345","201208091494345","000000130470","130470","","","INR","req.Refund","10","","Ext1","Ext2","Ext3","Ext4","Ext5");

$oPgResp=$oPostLibphp->postRelatedTxn($oMerchant);


 print "Response Code:".$oPgResp->getRespCode()."<br>";
 print "Response Message".$oPgResp->getRespMessage()."<br>";
 print "Transaction ID".$oPgResp->getTxnId()."<br>";
 print "Epg Transaction ID".$oPgResp->getEpgTxnId()."<br>";
 print "Auth Id Code :".$oPgResp->getAuthIdCode()."<br>";
 print "RRN :".$oPgResp->getRRN()."<br>";





 ?>