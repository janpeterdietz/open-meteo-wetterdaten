<?php

declare(strict_types=1);
	class openmeteoweather extends IPSModule
	{
		public function Create()
		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyBoolean('Active', false);

			$this->RegisterPropertyFloat('Latitude', 54.39594725660512);
			$this->RegisterPropertyFloat('Longitude', 10.122319471279694);

			$this->RegisterTimer("UpdateData", 15 * 60 * 1000, 'OMW_update_data(' . $this->InstanceID . ');');
		
			$this->RegisterAttributeString("raw_data", "");

			$omw_weather_code = [ 'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION ];

			$this->RegisterVariableString ("current_weahter_code", $this->Translate("Weather status"),  $omw_weather_code, 10) ;
			
			$omw_Temperature= [	'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
								'ICON' => 'Temperature',						
								'DIGITS' => 1,	
   								'SUFFIX' => ' °C'];


			$this->RegisterVariableFloat ("current_Temperature", $this->Translate("Temperature"),  $omw_Temperature, 20) ;
			$this->RegisterVariableInteger ("current_Humidity", $this->Translate("Humidity"),  "~Humidity", 30) ;
			$this->RegisterVariableFloat ("current_Dewpoint", $this->Translate("Dewpoint"),  $omw_Temperature, 40) ;


			$intervals = '[ {"ColorDisplay":-1,"IntervalMinValue":0,"IntervalMaxValue":2,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Stille ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1}, 
							{"ColorDisplay":-1,"IntervalMinValue":2,"IntervalMaxValue":6,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Leiser Zug ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1},
							{"ColorDisplay":-1,"IntervalMinValue":6,"IntervalMaxValue":12,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Leichte Briese ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1},
							{"ColorDisplay":-1,"IntervalMinValue":12,"IntervalMaxValue":20,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Schwache Briese ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1},
							{"IntervalMinValue":20,"IntervalMaxValue":29,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"mässige Briese ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":29,"IntervalMaxValue":39,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"frische Briese ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":39,"IntervalMaxValue":50,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Starker Wind ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":50,"IntervalMaxValue":62,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Steifer Wind ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":62,"IntervalMaxValue":75,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Stürmischer Wind ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":75,"IntervalMaxValue":89,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Sturm ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":89,"IntervalMaxValue":103,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Schwerer Sturm ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":103,"IntervalMaxValue":118,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Orkanartiger Sturm ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1},
							{"IntervalMinValue":117,"IntervalMaxValue":200,"ConstantActive":false,"ConstantValue":"","ConversionFactor":1,"PrefixActive":true,"PrefixValue":"Orkan ","SuffixActive":false,"SuffixValue":"","DigitsActive":false,"DigitsValue":0,"IconActive":false,"IconValue":"","ColorActive":false,"ColorValue":-1,"ColorDisplay":-1}]';
			
			$omw_wind_speed = [	'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
								'ICON' => 'wind',						
								'DIGITS' => 2,	
								'INTERVALS_ACTIVE' => true,
								'INTERVALS' => $intervals,
								'MAX' => 100,
            					'MIN' => 0,
   								'SUFFIX' => ' km/h'];
			$this->RegisterVariableFloat ("current_windspeed", $this->Translate("Windspeed"),  $omw_wind_speed, 50) ;
			

		
			$this->RegisterVariableFloat ("daily_Temperature_Min", $this->Translate("Temperature Min"),  $omw_Temperature, 22) ;
			$this->RegisterVariableFloat ("daily_Temperature_Max", $this->Translate("Temperature Max"),  $omw_Temperature, 24) ;
			
			$omw_wind_speed = [	'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
								'ICON' => 'wind',						
								'DIGITS' => 2,	
								'INTERVALS_ACTIVE' => false,
								'INTERVALS' => $intervals,
								'MAX' => 100,
            					'MIN' => 0,
   								'SUFFIX' => ' km/h'];

		
			$this->RegisterVariableFloat ("daily_wind_max", $this->Translate("Windspeed Max"),  $omw_wind_speed, 52) ;
			$this->RegisterVariableFloat ("daily_wind_guest_max", $this->Translate("Wind guest Max"),  $omw_wind_speed, 54) ;

			$omw_precipitation = [	'PRESENTATION' => VARIABLE_PRESENTATION_VALUE_PRESENTATION,
									'ICON' => 'Rainfall',
									'DIGITS' => 0,	
   									'SUFFIX' => ' mm'];
		
			$this->RegisterVariableFloat ("daily_precipitation_sum", $this->Translate("precipitation"), $omw_precipitation, 60) ;
		
	
		}

		public function Destroy()
		{
			//Never delete this line!
			parent::Destroy();
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();	

			if ($this->ReadPropertyBoolean('Active')) 
			{
                $this->SetTimerInterval('UpdateData', 15 * 60 * 1000);
                $this->SetStatus(102);
            } 
			else 
			{
                $this->SetTimerInterval('UpdateData', 0);
                $this->SetStatus(104);
            }


			$this->update_data();
		}
	
		public function update_data()
		{
			/*
			Weather variable documentation
			WMO Weather interpretation codes (WW)
			Code	Description
			0	Clear sky
			1, 2, 3	Mainly clear, partly cloudy, and overcast
			45, 48	Fog and depositing rime fog
			51, 53, 55	Drizzle: Light, moderate, and dense intensity
			56, 57	Freezing Drizzle: Light and dense intensity
			61, 63, 65	Rain: Slight, moderate and heavy intensity
			66, 67	Freezing Rain: Light and heavy intensity
			71, 73, 75	Snow fall: Slight, moderate, and heavy intensity
			77	Snow grains
			80, 81, 82	Rain showers: Slight, moderate, and violent
			85, 86	Snow showers slight and heavy
			95 *	Thunderstorm: Slight or moderate
			96, 99 *	Thunderstorm with slight and heavy hail
			*/
			/*

			$weather_code_en = array( 
					'0' => 'Clear sky',
					'1' => 'Mainly clear',
					'2' => 'Partly cloudy',
					'3' => 'Overcast',
					'45' => 'Fog and depositing rime fog',
					'48' => 'Fog and depositing rime fog',
					'51' => 'Drizzle: Light intensity',
					'53' => 'Drizzle: moderate intensity',
					'55' => 'Drizzle: dense intensity',
					'56' => 'Freezing Drizzle: Light intensity',
					'57' => 'Freezing Drizzle: dense intensity',
					'61' => 'Rain: Slight intensity',
					'63' => 'Rain: moderate intensity',
					'65' => 'Rain: heavy intensity',
					'66' => 'Freezing Rain: Light intensity',
					'67' => 'Freezing Rain: heavy intensity',
					'71' => 'Snow fall: Slight',
					'73' => 'Snow fall: moderate',
					'75' => 'Snow fall: heavy intensity',
					'77' => 'Snow grains',
					'80' => 'Rain showers: Slight',
					'81' => 'Rain showers: moderate',
					'82' => 'Rain showers: violent',
					'85' => 'Snow showers slight',
					'86' => 'Snow showers heavy',
					'95' => 'Thunderstorm: Slight or moderate',
					'96' => 'Thunderstorm with slight hail',
					'99' => 'Thunderstorm with heavy hail'
			);*/

			$weather_code_de = array( 
					'0' => 'Klarer Himmel',
					'1' => 'Überwiegend klar',
					'2' => 'Teilweise bewölkt',
					'3' => 'Bedeckt',
					'45' => 'Nebel und Raureifnebel',
					'48' => 'Nebel und Raureifnebel',
					'51' => 'Nickelregen: Leichte Intensität',
					'53' => 'Nickelregen: Mäßige Intensität',
					'55' => 'Nickelregen: Starke Intensität',
					'56' => 'Gefrierender Nieselregen: Leichte Intensität',
					'57' => 'Gefrierender Nieselregen: Starke Intensität',
					'61' => 'Regen: Leichte Intensität',
					'63' => 'Regen: Mäßige Intensität',
					'65' => 'Regen: Starke Intensität',
					'66' => 'Gefrierender Regen: Leichte Intensität',
					'67' => 'Gefrierender Regen: Starke Intensität',
					'71' => 'Schneefall: Leicht',
					'73' => 'Schneefall: Mäßig',
					'75' => 'Schneefall: Starke Intensität',
					'77' => 'Schneekörner',
					'80' => 'Regenschauer: Leicht',
					'81' => 'Regenschauer: Mäßig',
					'82' => 'Regenschauer: Heftig',
					'85' => 'Schneeschauer: Leicht',
					'86' => 'Schneeschauer: Stark',
					'95' => 'Gewitter: Leicht oder mäßig',
					'96' => 'Gewitter mit leichtem Hagel',
					'99' => 'Gewitter mit starkem Hagel'
			);

			$weather_code_icon = array( 
					'0' => 'sun',
					'1' => 'cloud-sun',
					'2' => 'cloud-sun',
					'3' => 'clouds',
					'45' => 'smog',
					'48' => 'smog',
					'51' => 'cloud-drizzle',
					'53' => 'cloud-rain',
					'55' => 'cloud-rain',
					'56' => 'cloud-rain',
					'57' => 'cloud-rain',
					'61' => 'cloud-drizzle',
					'63' => 'cloud-showers-heavy',
					'65' => 'cloud-showers-heavy',
					'66' => 'cloud-rain',
					'67' => 'cloud-rain',
					'71' => 'cloud-snow',
					'73' => 'cloud-snow',
					'75' => 'cloud-snow',
					'77' => 'cloud-snow',
					'80' => 'cloud-rain',
					'81' => 'cloud-showers-heavy',
					'82' => 'cloud-showers-heavy',
					'85' => 'cloud-sleet',
					'86' => 'cloud-rain',
					'95' => 'cloud-bolt',
					'96' => 'cloud-bolt',
					'99' => 'cloud-bolt'
			);


			$lat = $this->ReadPropertyFloat('Latitude');
			$lon = $this->ReadPropertyFloat('Longitude');

		
			{ // lese die Daten
				// Content
				$currentvalues = '&current=temperature_2m,relative_humidity_2m,wind_speed_10m,dew_point_2m,weather_code,precipitation';
				$dailyvalues = '&daily=temperature_2m_max,temperature_2m_min,wind_gusts_10m_max,wind_speed_10m_max,precipitation_sum';
				$timezone = '&timezone=Europe%2FBerlin'; 

				
				$content = 'https://api.open-meteo.com/v1/forecast?latitude='.   $lat .'&longitude='. $lon . $dailyvalues. $currentvalues. $timezone;
				$data = @Sys_GetURLContent($content);

				if ($data == false)
				{
					IPS_LogMessage( IPS_GetName($_IPS['SELF']) .' '. $_IPS['SELF'], 'Zugriff auf Open Meto App fehlgeschlagen');
					return;
				}

				$this->WriteAttributeString("raw_data", $data);
			}

			$data = $this->ReadAttributeString("raw_data" );
			$raw_data_array = json_decode($data, true);

			$time_zone = $raw_data_array['timezone_abbreviation'];
			$timestamp_str= date('Y-m-d H:i:s', strtotime($raw_data_array['current']['time'] .$time_zone)   );
			$timestamp_int = strtotime($timestamp_str);
			$time_of_data = date('H:i:s', strtotime($raw_data_array['current']['time'] .$time_zone)   );
			
			IPS_SetName($this->InstanceID, 'Wetterdaten von: '. $time_of_data );

			$minute = date('i', time() );
			$next = (((int)($minute / 15)) + 1)*15 - $minute;
			$this->SetTimerInterval("UpdateData", $next * 60 * 1000);


			$weahter_code = $raw_data_array['current']['weather_code'];

			$this->SetValue("current_weahter_code", $weather_code_de[$weahter_code]);
			$id_weahter_code = IPS_GetObjectIDByIdent('current_weahter_code', $this->InstanceID);
			IPS_SetIcon($id_weahter_code, $weather_code_icon[$weahter_code]);
			

			$this->SetValue("current_Temperature", $raw_data_array['current']['temperature_2m']);
			$this->SetValue("current_Humidity", $raw_data_array['current']['relative_humidity_2m']);
			$this->SetValue("current_Dewpoint", $raw_data_array['current']['dew_point_2m']);
			$this->SetValue("current_windspeed", $raw_data_array['current']['wind_speed_10m']);

			
			// Daily
			foreach($raw_data_array['daily']['time'] as $key=>$value)
			{
				$new_key = $value;
				$forcast['temperature_min'][$new_key] = $raw_data_array['daily']['temperature_2m_min'][$key];
				$forcast['temperature_max'][$new_key] = $raw_data_array['daily']['temperature_2m_max'][$key];
				$forcast['wind_speed_max'][$new_key] = $raw_data_array['daily']['wind_speed_10m_max'][$key];  
				$forcast['wind_gusts_max'][$new_key] = $raw_data_array['daily']['wind_gusts_10m_max'][$key];  
				$forcast['precipitation_sum'][$new_key] = $raw_data_array['daily']['precipitation_sum'][$key];  
		
			}

			$today = date('Y-m-d', time());

			$this->SetValue ("daily_Temperature_Min", $forcast['temperature_min'][$today]) ;
			$this->SetValue ("daily_Temperature_Max", $forcast['temperature_max'][$today]) ;

			$this->SetValue ("daily_wind_max", $forcast['wind_speed_max'][$today]) ;
			$this->SetValue ("daily_wind_guest_max", $forcast['wind_gusts_max'][$today]) ;
			
			$this->SetValue ("daily_precipitation_sum", $forcast['precipitation_sum'][$today]) ;
		
			

		}
	}