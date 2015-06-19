<?php

// jsonの読み込み
$kutc_tt = file_get_contents('./tt/kutc.json');
$takatsuki_tt = file_get_contents('./tt/takatsuki.json');
$tonda_tt = file_get_contents('./tt/tonda.json');

$kutc = json_decode($kutc_tt,true);
$takatsuki = json_decode($takatsuki_tt,true);
$tonda = json_decode($tonda_tt,true);

// 時刻表データを一つの連想配列にまとめる
$timeTable = array('kutc' => $kutc['kutc'], 'takatsuki' => $takatsuki['takatsuki'], 'tonda' => $tonda['tonda']);

// 連想配列のエラー回避
function getElement($timeTable, $key) {
	return isset($timeTable[$key]) ? $timeTable[$key] : null;
}

// (時刻表, 出発点, 行き先, 年, 月, 日, 時, 分) で次のバスの情報を戻す
function getNextBus($timeTable, $from, $to, $year, $month, $day, $hour, $minute){
	$from_tt = getElement($timeTable, $from);
	$to_tt = getElement($from_tt, $to);

	// 日付のタイプ(week, sun, sat)を取得
	$daytype = getDayType($year, $month, $day);

	// 学休期間かどうかを調べる
	$inVacation = dateIsInVacation($year, $month, $day);

	if($daytype == null){
		return null;
	}

	$day_tt = getElement($to_tt, $daytype);
	$hour_tt = getElement($day_tt, $hour);

	// 次のバスが見つかるまで時刻表を読み込む
	while(true){
		if($hour_tt != null){
			foreach($hour_tt as $bus){
				if($bus[0] >= $minute){
					$busType = $bus[2];
					if(!$inVacation && $busType == 2){
						// 通常日かつ学休日のみ運行バスのとき
						continue;
					}
					if($inVacation && ($busType == 1 || $busType == 3)){
						// 学休日かつ運休のバスのとき
						continue;
					}

					// 戻り値 [年, 月, 日, 時, (分, 行き先, 運行タイプ)]
					return Array($year, $month, $day, $hour, $bus);
				}
			}
		}

		// 時刻, 日付の更新
		$minute = 0;
		if($hour < 23){
			$hour++;
		}else{
			// 次の日
			$day++;
			$hour = 0;
			// 存在する日付か
			if(!checkdate($month, $day, $year)){
				// 次の月
				if($month < 12){
					$month++;
					$day = 1;
				}else{
					// 次の年
					$year++;
					$month = $day = 1;
				}
			}
			$inVacation = dateIsInVacation($year, $month, $day);
			$daytype = getDayType($year, $month, $day);
			$day_tt = getElement($to_tt, $daytype);
		}
		$hour_tt = getElement($day_tt, $hour);
	}
}

function getDayType($year, $month, $day){
	if(!checkdate($month, $day, $year)){
		return null;
	}

	// 曜日の取得
	$week = date('w', strtotime($year.'-'.$month.'-'.$day));
	$daytype = null;

	// 曜日によって日付のタイプを分ける
	if($week == 0){
		$daytype = 'sun';
	}elseif($week == 6){
		$daytype = 'sat';
	}else{
		$daytype = 'week';
	}

	return $daytype;
}

function dateIsInVacation($year, $month, $day){
	if(!checkdate($month, $day, $year)){
		return null;
	}

	$date = strtotime($year.'-'.$month.'-'.$day);

	// 以下の学休期間は平成27年度のもの，年度によって変更の必要あり
	$springvStart = strtotime($year.'-01-31');
	$springvEnd = strtotime($year.'-03-31');
	$summervStart = strtotime($year.'-08-04');
	$summervEnd = strtotime($year.'-09-20');
	$wintervStart = strtotime($year.'-12-26');
	$wintervEnd = strtotime($year.'-01-06');

	// 学休期間内ならばtrueを戻す
	if(($springvStart <= $date && $date <= $springvEnd) ||
			($summervStart <= $date && $date <= $summervEnd) ||
			($wintervStart <= $date && $date <= $wintervEnd)){
		return true;
	}

	return false;
}

var_dump(getNextBus($timeTable, 'tonda', 'kutc', 2015, 8, 10, 8, 22));

?>