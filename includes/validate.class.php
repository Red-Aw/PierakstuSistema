<?php

	class Validate
	{
		static function IsValidTime($text)
		{
			return preg_match("/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/", $text);
		}

		static function IsValidFloatNumber($number)
		{
			return preg_match("/^\d{1,12}([\.]\d{1,3}+)?$/", $number);
		}

		static function IsValidFloatNumberWithTwoDigitsAfterDot($number)
		{
			return preg_match("/^\d{1,12}([\.]\d{1,2}+)?$/", $number);
		}

		static function IsValidDate($text)
		{
			return preg_match("/^\d{4}[\-\/\s]?((((0[13578])|(1[02]))[\-\/\s]?(([0-2][0-9])|(3[01])))|(((0[469])|(11))[\-\/\s]?(([0-2][0-9])|(30)))|(02[\-\/\s]?[0-2][0-9]))$/", $text);
		}

		static function IsValidPeriod($text)
		{
			return preg_match("/^\d{4}[-](0[1-9]|1[012])$/", $text);
		}

		static function IsValidIntegerNumber($number)
		{
			return preg_match("/^\d{1,12}+$/", $number);
		}

		static function IsValidTextLength($text)
		{
			if(mb_strlen($text) < 3 || mb_strlen($text) > 255)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidText($text)
		{
			return preg_match("/^[0-9\p{L}][\p{L}\/0-9\s.,_-]+$/u", $text);
		}

		static function IsValidNameLength($text)
		{
			if(mb_strlen($text) < 3 || mb_strlen($text) > 50)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidName($text)
		{
			return preg_match("/^\p{L}[\p{L}\s-]+$/u", $text);
		}

		static function IsValidPositionLength($text)
		{
			if(mb_strlen($text) < 3 || mb_strlen($text) > 255)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidUsername($text)
		{
			return preg_match("/^[a-zA-Z0-9]*$/", $text);
		}

		static function IsValidPasswordLength($text)
		{
			if(mb_strlen($text) < 8 || mb_strlen($text) > 64)
			{
				return false;
			}
			else
			{
				return true;
			}
		}

		static function IsValidPassword($text)
		{
			return preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,64}$/", $text);
		}

		static function IsValidHours($text)
		{
			return preg_match("/^([1-9]|1[0-9]|2[0-4])$/", $text);
		}

		static function IsArrayEmpty($array)
		{
			foreach($array as $key => $value)
			{
				if(!empty($value))
				{
					return false;
				}
			}
			return true;
		}

		static function IsArrayEmptyFromTo($array, $from_index, $to_index)
		{
			for($i = $from_index; $i < $to_index; $i++)
			{
				if(!empty($array[$i]))
				{
					return false;
				}
			}
			return true;
		}

		static function IsValidActNumber($number)
		{
			return preg_match("/^([0-9][0-9]{0,2}|1000)$/", $number);
		}

		static function IsValidPersonNumber($number)
		{
			return preg_match("/^\d{6}-\d{5}$/", $number);
		}

		static function IsValidWorkingHours($text)
		{
			return preg_match("/^[aAcCnNsS1-8]?$/", $text);
		}

		static function IsValidOvertimeHours($text)
		{
			return preg_match("/^[1-8]?$/", $text);
		}

		static function IsValidDropdownWorkingHours($value)
		{
			for($i = 1; $i <= 11; $i++)
			{
				if($value == $i)
				{
					return true;
				}
			}
			return false;
		}

		static function IsValidDropdownWorkingHoursSortingEmployee($value)
		{
			if(!empty($value))
			{
				for($i = 1; $i <= 11; $i++)
				{
					if($value == $i)
					{
						return true;
					}
				}
				return false;
			}
			return true;
		}
	}

?>