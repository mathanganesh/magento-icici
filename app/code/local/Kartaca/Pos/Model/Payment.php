<?php
/**
 * Description of Payment
 *
 * @author roysimkes
 */

class Kartaca_Pos_Model_Payment extends Mage_Payment_Model_Method_Abstract
{
    protected $_isGateway = true;
    protected $_canAuthorize = true;
    protected $_canUseCheckout = true;

    protected $_code = "pos";
    protected $_formBlockType = 'pos/payment_form_pos';

	
	
    public function authorize(Varien_Object $payment, $amount)
    {
	   
        if(!isset($_POST['success']))
		{
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/BillToAddress.php");
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/CardInfo.php");
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/Merchant.php");
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/MPIData.php");
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/ShipToAddress.php");
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/PGResponse.php");
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/PostLibPHP.php");
				require_once(Mage::getBaseDir('lib')."/SFAClient/Sfa/PGReserveData.php");

				$orderId = $payment->getOrder()->getIncrementId();
				try {
				$amount=1;
				$quote = Mage::getSingleton('checkout/session')->getQuote();
				$billingAddress = $quote->getBillingAddress();
				$country = $billingAddress->getCountryId();
				$city = $billingAddress->getCity();
				$zipcode = $billingAddress->getPostcode(); 
				$shippingAddress = $quote->getShippingAddress();      

				$oMPI 			= 	new MPIData();
				$oCI			=	new	CardInfo();
				$oPostLibphp	=	new	PostLibPHP();
				$oMerchant		=	new	Merchant();
				$oBTA			=	new	BillToAddress();
				$oSTA			=	new	ShipToAddress();
				$oPGResp		=	new	PGResponse();
				$oPGReserveData =	new PGReserveData();

				$returnUrl=Mage::helper('core/url')->getHomeUrl().'pos';
				 
				$oMerchant->setMerchantDetails("96013917","96013917","96013917","142.4.10.91",rand()."",$orderId,$returnUrl,"POST","INR",$orderId,"req.Sale",$amount,"","Ext1","true","Ext3","Ext4","EZY");
				
				$oBTA->setAddressDetails ("EZY",$billingAddress->getFirstname().''.$billingAddress->getlastname(),$billingAddress->getStreet(1),$billingAddress->getStreet(2),"",$billingAddress->getRegion(),$billingAddress->getRegion(),$billingAddress->getPostcode(),'IND',$billingAddress->getEmail());
				
				$oSTA->setAddressDetails ($billingAddress->getStreet(1),$billingAddress->getStreet(2),"",$billingAddress->getRegion(),$billingAddress->getRegion(),$billingAddress->getPostcode(),'IND',$billingAddress->getEmail());


				$oPGResp=$oPostLibphp->postSSL($oBTA,$oSTA,$oMerchant,$oMPI,$oPGReserveData);


				if($oPGResp->getRespCode() == '000'){
				  $url	=$oPGResp->getRedirectionUrl();
				  $out=array('redirect'=>$url);
				  die(json_encode($out));
				}else{
				   print "Error Occured.<br>";
				   print "Error Code:".$oPGResp->getRespCode()."<br>";
				   print "Error Message:".$oPGResp->getRespMessage()."<br>";
				   die();
				}

				} catch (Exception $e) {
				$payment->setStatus(self::STATUS_ERROR);
				$payment->setAmount($amount);
				$payment->setLastTransId($orderId);
				$this->setStore($payment->getOrder()->getStoreId());
				Mage::throwException($e->getMessage());
				}
    }
	else
	{
			$isPaymentAccepted = 1;
			if ($isPaymentAccepted) {
				$this->setStore($payment->getOrder()->getStoreId());
				$payment->setStatus(self::STATUS_APPROVED);
				$payment->setAmount($amount);
				$payment->setLastTransId($orderId);
			} else {
				$this->setStore($payment->getOrder()->getStoreId());
				$payment->setStatus(self::STATUS_ERROR);
				Mage::throwException("Payment is not approved");
			}
			return $this;
		}
    }
}

