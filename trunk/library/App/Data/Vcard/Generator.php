<?php

class App_Data_Vcard_Generator
{

	protected static function _renderTimezone($vcardObject)
	{
		if ($vcardObject->timezone)
		{
			return "TZ:" . $vcardObject->timezone . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderMailer($vcardObject)
	{
		if ($vcardObject->mailer)
		{
			return "MAILER:" . $vcardObject->mailer . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderGeolocation($vcardObject)
	{
		if ($vcardObject->geolocation)
		{
			return "GEO:" . $vcardObject->geolocation . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderBirthday($vcardObject)
	{
		if ($vcardObject->birthday)
		{
			return "BDAY:" . $vcardObject->birthday . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderFullname($vcardObject)
	{
		return "FN:" . $vcardObject->fullname . App_Data_Vcard::LINEBREAK;
	}

	protected static function _renderName($vcardObject)
	{
		return "N:"
		. $vcardObject->lastname . ";"
		. $vcardObject->firstname . ";"
		. $vcardObject->additionalnames . ";"
		. $vcardObject->nameprefix . ";"
		. $vcardObject->namesuffix . App_Data_Vcard::LINEBREAK;
	}

	protected static function _renderVersion($version)
	{
		return "VERSION:" . $version . App_Data_Vcard::LINEBREAK;
	}

	protected static function _renderAddress($vcardObject)
	{

		if ($vcardObject->address)
		{
			$addressString = "";
			foreach ($vcardObject->address as $address)
			{
				$addressTypeString = '';
				if ($address['type'])
				{
					foreach ($address['type'] as $addressType)
					{
						$addressTypeString .= ";" . $addressType;
					}
				}
				$addressString .= "ADR" . $addressTypeString . ":"
								. $address['postofficeaddress'] . ";"
								. $address['extendedaddress'] . ";"
								. $address['street'] . ";"
								. $address['city'] . ";"
								. $address['state'] . ";"
								. $address['zip'] . ";"
								. $address['country'] . App_Data_Vcard::LINEBREAK;
			}
			return $addressString;
		}
	}

	protected static function _renderBegin()
	{
		return "BEGIN:VCARD" . App_Data_Vcard::LINEBREAK;
	}

	protected static function _renderTelephone($vcardObject)
	{
		if ($vcardObject->telephone)
		{
			$telephoneString = "";
			foreach ($vcardObject->telephone as $telephone)
			{
				$telephoneTypeString = '';

				if ($telephone['type'])
				{
					foreach ($telephone['type'] as $telephoneType)
					{
						$telephoneTypeString .= ";" . $telephoneType;
					}
				}
				$telephoneString .= "TEL" . $telephoneTypeString . ":" .
								$telephone['value'] . App_Data_Vcard::LINEBREAK;
			}
			return $telephoneString;
		}
	}

	protected static function _renderEmail($vcardObject)
	{
		if ($vcardObject->email)
		{
			$emailString = "";
			foreach ($vcardObject->email as $email)
			{
				$emailTypeString = '';
				if ($email['type'])
				{
					foreach ($email['type'] as $emailType)
					{
						$emailTypeString .= ";" . $emailType;
					}
				}
				$emailString .= "EMAIL" . $emailTypeString . ":" .
								$email['value'] . App_Data_Vcard::LINEBREAK;
			}
			return $emailString;
		}
	}

	protected static function _renderTitle($vcardObject)
	{
		if ($vcardObject->title)
		{
			return "TITLE:" . $vcardObject->title . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderRole($vcardObject)
	{
		if ($vcardObject->role)
		{
			return "ROLE:" . $vcardObject->role . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderOrganization($vcardObject)
	{
		if ($vcardObject->organization ||
						$vcardObject->department ||
						$vcardObject->subdepartment)
		{
			return "ORG:"
			. $vcardObject->organization . ";"
			. $vcardObject->department . ";"
			. $vcardObject->subdepartment . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderRevision($vcardObject)
	{
		if ($vcardObject->revision)
		{
			return "REV:" . $vcardObject->revision . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderUrl($vcardObject)
	{
		if ($vcardObject->url)
		{
			$urlString = "";
			foreach ($vcardObject->url as $url)
			{
				$emailTypeString = '';
				if ($url['type'])
				{
					foreach ($url['type'] as $emailType)
					{
						$emailTypeString .= ";" . $emailType;
					}
				}
				$urlString .= "URL" . $emailTypeString . ":" .
								$url['value'] . App_Data_Vcard::LINEBREAK;
			}
			return $urlString;
		}
	}

	protected static function _renderUid($vcardObject)
	{
		if ($vcardObject->uid)
		{
			return "UID:" . $vcardObject->uid . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderGender($vcardObject)
	{
		if ($vcardObject->gender)
		{
			return "X-GENDER:" . $vcardObject->gender . App_Data_Vcard::LINEBREAK;
		}
	}

	protected static function _renderNickname($vcardObject, $version)
	{
		if ($version == App_Data_Vcard::VERSION30)
		{
			if ($vcardObject->nickname)
			{
				return "NICKNAME:" . $vcardObject->nickname . App_Data_Vcard::LINEBREAK;
			}
		}
	}

	protected static function _renderInstantmessenger($vcardObject)
	{
		if ($vcardObject->im)
		{
			$imString = "";
			foreach ($vcardObject->im as $im)
			{
				$imTypeString = '';
				if ($im['type'])
				{
					foreach ($im['type'] as $imType)
					{
						$imTypeString .= ";" . $imType;
					}
				}
				$imString .= $im['messenger'] . $imTypeString . ":" .
								$im['value'] . App_Data_Vcard::LINEBREAK;
			}
			return $imString;
		}
	}

	protected static function _renderEnd()
	{
		return "END:VCARD";
	}

	public static function generate($vcardObjects = null,
																 $version = App_Data_Vcard::VERSION30)
	{
		if (!is_array($vcardObjects))
		{
			$vcardObjects = array($vcardObjects);
		}

		$vcardString = "";

		foreach ($vcardObjects as $vcardObject)
		{
			if (!empty($vcardString))
			{
				$vcardString .= App_Data_Vcard::LINEBREAK;
			}
			$vcardString .= self::_renderBegin();
			$vcardString .= self::_renderVersion($version);
			$vcardString .= self::_renderFullname($vcardObject);
			$vcardString .= self::_renderName($vcardObject);
			$vcardString .= self::_renderBirthday($vcardObject);
			$vcardString .= self::_renderAddress($vcardObject);
			$vcardString .= self::_renderTelephone($vcardObject);
			$vcardString .= self::_renderEmail($vcardObject);
			$vcardString .= self::_renderInstantmessenger($vcardObject);
			$vcardString .= self::_renderTitle($vcardObject);
			$vcardString .= self::_renderRole($vcardObject);
			$vcardString .= self::_renderOrganization($vcardObject);
			$vcardString .= self::_renderRevision($vcardObject);
			$vcardString .= self::_renderUrl($vcardObject);
			$vcardString .= self::_renderUid($vcardObject);
			$vcardString .= self::_renderNickname($vcardObject, $version);
			$vcardString .= self::_renderGender($vcardObject);
			$vcardString .= self::_renderGeolocation($vcardObject);
			$vcardString .= self::_renderMailer($vcardObject);
			$vcardString .= self::_renderTimezone($vcardObject);
			$vcardString .= self::_renderEnd();
		}

		return $vcardString;
	}
}