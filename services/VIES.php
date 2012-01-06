<?php
/**
 * VAT Information Exchange Service
 *
 * @name		VIES
 * @version		1.0
 * @since		2011-12-14
 * @author		Vincent Verbruggen
 */
class VIES
{
	/**
	 * The URL of the VAT Information Exchange Service
	 * @var string
	 */
	protected static $_viesUrl = 'http://ec.europa.eu/taxation_customs/vies/checkVatService.wsdl';
	
	/**
	 * The SoapClient object
	 * @var SoapClient
	 */
	protected $_client;
	
	
	/**
	 * Class constructor
	 */
	public function __construct ()
	{
		$this->_client = new \SoapClient(self::$_viesUrl);
	}
	
	
	/**
	 * Check the validity of a European VAT number
	 * @param string $vatNumber		A European VAT number
	 * @return bool
	 */
	public function checkVAT ($vatNumber)
	{
		$vatNumber = $this->sanitize($vatNumber);
		
		if ($vatNumber)
		{
			return $this->_client->checkVat($vatNumber)->valid;
		}
		
		return false;
	}
	
	
	/**
	 * Get more information about a given VAT number
	 * @param string $vatNumber		A European VAT number
	 * @return array|bool			Returns false if the VAT number is not valid, or an array containing more information about the VAT number.
	 * [PHP]
	 * (object) array(
	 * 	'countryCode'		=> 'BE',
	 * 	'vatNumber'			=> '0883923584',
	 * 	'requestDate'		=> '2011-12-14+01:00',
	 * 	'valid'				=> true
	 * 	'traderName'		=> 'GCV TSE-WebDesign',
	 * 	'traderAddress'		=> 'Ezenhoek 53
	 * 2590 Berlaar',
	 * 	'requestIdentifier'	=> ''
	 * );
	 * [/PHP]
	 */
	public function getInfo ($vatNumber)
	{
		if ($this->checkVAT($vatNumber))
		{
			return $this->_client->checkVatApprox($this->sanitize($vatNumber));
		}
		
		return false;
	}
	
	
	/**
	 * Sanitize the VAT number and split the country code from the VAT number
	 * @param string $vatNumber		A European VAT number
	 * @return array|bool			Returns false if the VAT number does not meet the required standards to make a call to the SOAP client,
	 *								or an array containing the country code and the VAT number.
	 * [PHP]
	 * (object) array(
	 * 	'countryCode'	=> 'BE',
	 * 	'vatNumber'		=> '0883923584'
	 * );
	 * [/PHP]
	 */
	protected function sanitize ($vatNumber)
	{
		$vatNumber = preg_replace('/[^a-z0-9]+/i', '', $vatNumber);
		
		if (strlen($vatNumber)<=2)
		{
			return false;
		}
		
		return array(
				'countryCode'	=> substr($vatNumber, 0, 2),
				'vatNumber'		=> substr($vatNumber, 2)
			);
	}
}
?>