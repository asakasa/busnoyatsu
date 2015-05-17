<?php

// 出発点を引数に与えたらそこの時刻表を取得する関数......になる予定
function get_tt($location) {
	switch ($location) {
		case kutc:
			$kutc_tt = file_get_contents('./tt/kutc.json');// jsonの読み込み
			$kutc = json_decode($kutc_tt,true);// jsonの読み込み
			$timeTable = array('kutc' => $kutc['kutc']);// 時刻業データを連想配列に
			return $timeTable;
			break;
		case takatsuki:
			$takatsuki_tt = file_get_contents('./tt/takatsuki.json');// 同上
			$takatsuki = json_decode($takatsuki_tt,true);
			$timeTable = array('takatsuki' => $takatsuki['takatsuki']);
			return $timeTable;
			break;
		case tonda:
			$tonda_tt = file_get_contents('./tt/tonda.json');// 同上
			$tonda = json_decode($tonda_tt,true);
			$timeTable = array('tonda' => $tonda['tonda']);
			return $timeTable;
			break;
		default:
			break;
	}
}