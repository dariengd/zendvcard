<?php

class App_Data_Vcard_Parser
{
	protected $_content = null;
	protected $_vcardCacheObject = null;

	public function __construct($filename)
	{
		if (is_file($filename))
		{
			$this->_content = file_get_contents($filename);
		}
		else
		{
			$this->_content = $filename;
		}
	}

	protected function _parseFullname($value, $args = array())
	{
		$this->_vcardCacheObject->setFullname($value);
	}

	protected function _parseName($value, $args = array())
	{
		//var_dump($args);
		$value = explode(';', $value);
		if (isset($value[0]))
		{
			$this->_vcardCacheObject->setLastname($value[0]);
		}
		if (isset($value[1]))
		{
			$this->_vcardCacheObject->setFirstname($value[1]);
		}
		if (isset($value[2]))
		{
			$this->_vcardCacheObject->setAdditionalNames($value[2]);
		}
		if (isset($value[3]))
		{
			$this->_vcardCacheObject->setNamePrefix($value[3]);
		}
		if (isset($value[4]))
		{
			$this->_vcardCacheObject->setNameSuffix($value[4]);
		}
	}

	protected function _parseBirthday($value, $args = array())
	{
		$this->_vcardCacheObject->setBirthday($value);
	}

	protected function _parseGender($value, $args = array())
	{
		$this->_vcardCacheObject->setGender($value);
	}

	protected function _parseNickname($value, $args = array())
	{
		$this->_vcardCacheObject->setNickname($value);
	}

	protected function _parseUid($value, $args = array())
	{
		$this->_vcardCacheObject->setUid($value);
	}

	protected function _parseRole($value, $args = array())
	{
		$this->_vcardCacheObject->setRole($value);
	}

	protected function _parseTitle($value, $args = array())
	{
		$this->_vcardCacheObject->setTitle($value);
	}

	protected function _parseOrganization($value, $args = array())
	{
		$value = explode(';', $value);
		if (isset($value[0]))
		{
			$this->_vcardCacheObject->setOrganization($value[0]);
		}
		if (isset($value[1]))
		{
			$this->_vcardCacheObject->setDepartment($value[1]);
		}
		if (isset($value[2]))
		{
			$this->_vcardCacheObject->setSubDepartment($value[2]);
		}
	}

	protected function _parseRevision($value, $args = array())
	{
		$this->_vcardCacheObject->setRevision($value);
	}

	protected function _parseVersion($value, $args = array())
	{
		//$this->_vcardCacheObject->setVersion($value, $args);
	}

	protected function _parseAddress($value, $args = array())
	{
		$subArray = array();
		foreach ($args as $typeCache)
		{
			$typeCache = strtoupper($typeCache);
			if (strpos($typeCache, 'TYPE=') === 0)
			{
				$subArray = array_merge($subArray, explode(",", substr($typeCache, 5)));
			}
			else
			{
				$subArray[] = $typeCache;
			}
		}
		$value = explode(';', $value);
		$this->_vcardCacheObject->addAddress(array(
				'postofficeaddress' => (isset($value['0']) ? $value['0'] : ''),
				'extendedaddress' => (isset($value['1']) ? $value['1'] : ''),
				'street' => (isset($value['2']) ? $value['2'] : ''),
				'city' => (isset($value['3']) ? $value['3'] : ''),
				'state' => (isset($value['4']) ? $value['4'] : ''),
				'zip' => (isset($value['5']) ? $value['5'] : ''),
				'country' => (isset($value['6']) ? $value['6'] : ''),
				'type' => $subArray));
	}

	protected function _parseTelephone($value, $args = array())
	{
		$subArray = array();
		foreach ($args as $typeCache)
		{
			$typeCache = strtoupper($typeCache);
			if (strpos($typeCache, 'TYPE=') === 0)
			{
				$subArray = array_merge($subArray, explode(",", substr($typeCache, 5)));
			}
			else
			{
				$subArray[] = $typeCache;
			}
		}
		$this->_vcardCacheObject->addPhonenumber($value, $subArray);
	}

	protected function _parseEmail($value, $args = array())
	{
		$subArray = array();
		foreach ($args as $typeCache)
		{
			$typeCache = strtoupper($typeCache);
			if (strpos($typeCache, 'TYPE=') === 0)
			{
				$subArray = array_merge($subArray, explode(",", substr($typeCache, 5)));
			}
			else
			{
				$subArray[] = $typeCache;
			}
		}

		$this->_vcardCacheObject->addEmail($value, $subArray);
	}

	protected function _parseUrl($value, $args = array())
	{
		$subArray = array();
		foreach ($args as $typeCache)
		{
			$typeCache = strtoupper($typeCache);
			if (strpos($typeCache, 'TYPE=') === 0)
			{
				$subArray = array_merge($subArray, explode(",", substr($typeCache, 5)));
			}
			else
			{
				$subArray[] = $typeCache;
			}
		}
		$this->_vcardCacheObject->addUrl($value, $subArray);
	}

	protected function _parseIm($value, $messenger, $args = array())
	{
		$subArray = array();
		foreach ($args as $typeCache)
		{
			$typeCache = strtoupper($typeCache);
			if (strpos($typeCache, 'TYPE=') === 0)
			{
				$subArray = array_merge($subArray, explode(",", substr($typeCache, 5)));
			}
			else
			{
				$subArray[] = $typeCache;
			}
		}

		$this->_vcardCacheObject->addInstantmessenger($value, $messenger, $subArray);
	}

	protected function _parseGeo($value, $args = array())
	{
		$this->_vcardCacheObject->setGeolocation($value);
	}

	protected function _parseMailer($value, $args = array())
	{
		$this->_vcardCacheObject->setMailer($value);
	}

	protected function _parseTimezone($value, $args = array())
	{
		$this->_vcardCacheObject->setTimezone($value);
	}

	// Returns Vcard Object
	public function parse()
	{
		$vcardObjectArray = array();
		$commands = array("FN" => "fullname",
				"N" => "name",
				"BDAY" => "birthday",
				"ADR" => "address",
				"TEL" => "telephone",
				"EMAIL" => "email",
				"X-GENDER" => 'gender',
				"REV" => 'revision',
				"VERSION" => 'version',
				"ORG" => 'organization',
				"UID" => 'uid',
				"URL" => 'url',
				"TITLE" => 'title',
				"ROLE" => 'role',
				"NICKNAME" => 'nickname',
				"X-AIM" => 'im',
				"X-ICQ" => 'im',
				"X-JABBER" => 'im',
				"X-MSN" => 'im',
				"X-YAHOO" => 'im',
				"X-SKYPE" => 'im',
				"X-SKYPE-USERNAME" => 'im',
				"X-GADUGADU" => 'im',
				"GEO" => 'geo',
				"MAILER" => 'mailer',
				"TZ" => 'timezone');
		$this->_content = str_replace(array(App_Data_Vcard::LINEBREAK, "\r"), "\n", $this->_content);

		// RFC2425 5.8.1.  Line delimiting and folding
		// Unfolding is accomplished by regarding CRLF immediately
        //followed by a white space character (namely HTAB ASCII decimal 9 or
        //SPACE ASCII decimal 32) as equivalent to no characters at all (i.e.,
        //the CRLF and single white space character are removed).   
		$this->_content = preg_replace("/\n(?:[ \t])/", "", $this->_content);
        //var_dump($this->_content);
		$lines = explode("\n", $this->_content);

		//Zend_Debug::dump($lines);

		foreach ($lines as $line)
		{
			$line = trim($line);
			//echo $f . $line;
			if (strtoupper($line) == "BEGIN:VCARD")
			{
				//echo 'Begin';
				$this->_vcardCacheObject = new App_Data_Vcard();
			}
			elseif (strtoupper($line) == "END:VCARD")
			{
				//echo 'End!!!';
				$vcardObjectArray[] = $this->_vcardCacheObject;
			}
			elseif ($line != null)
			{
				$type = '';
				$value = '';
				//var_dump(explode(':', $line, 2));
				list($type, $value) = explode(':', $line, 2);
				$types = explode(';', $type);

				$command = strtoupper($types[0]);
				//var_dump($types);
				array_shift($types);
				$i = 0;
				foreach($types as $type)
				{
					if(strpos(strtolower($type), 'base64'))
					{
						$value = base64_decode($value);
						unset($types[$i]);
					}
					elseif(strpos(strtolower($type), 'encoding=b'))
					{
						$value = base64_decode($value);
						unset($types[$i]);
					}
					elseif(strpos(strtolower($type), 'quoted-printable'))
					{
						$value = quoted_printable_decode($value);
						unset($types[$i]);
					}
					elseif(strpos(strtolower($type), 'charset=') === 0)
					{
					try {
						$value = mb_convert_encoding($value, "UTF-8", substr($type, 8));
					}
					catch (Exception $e)
					{
					}
					 unset($types[$i]);
					}
					$i++;
				}
				if (in_array(strtoupper($command), array_keys($commands)))
				{
					if ($commands[$command] == 'im')
					{
						call_user_func(array($this, "_parse" . ucfirst($commands[$command])), $value, $command, $types);
					}
					elseif (isset($types[0]))
					{
						call_user_func(array($this, "_parse" . ucfirst($commands[$command])), $value, $types);
					}
					else
					{
						call_user_func(array($this, "_parse" . ucfirst($commands[$command])), $value);
					}
				}
			}
		}

		return $vcardObjectArray;
	}
}
