<?php
// 자바스크립트에서 받아옴
$rrn = $_GET['number'];

// - 를 기준으로 나눔
$split_rrn = explode('-',$rrn);

// 앞의 숫자에서 년, 월, 일을 구함
$year = substr($split_rrn[0],0,2);
$month = substr($split_rrn[0],2,2);
$day = substr($split_rrn[0],4,2);
$gender = "남성";

// 주민 숫자 하나하나 나눠 저장할배열
$rrnArray = Array();
$check = true;

// 주민번호 하나하나 자르기
for($i = 0; $i < 13; $i++){
    if($i < 6){
        array_push($rrnArray, substr($split_rrn[0],$i,1));
    } else {
        array_push($rrnArray, substr($split_rrn[1],$i-6,1));
    }
}

$ckNumber = 2;                  // 체크 수
$SUM = 0;                       // 체크 수 * 주민번호를 누적할 변수

for($i = 0; $i < 12; $i++){
    $SUM = $SUM + ($rrnArray[$i] * $ckNumber);
    $ckNumber++;
    if($ckNumber > 9){
        $ckNumber = 2;
    }
}

$ckResult = 11 - ($SUM%11);

if($ckResult == $rrnArray[12]){
    // 유효한 주민등록번호
    switch ($rrnArray[6]){
        // 성별과 출생 년도 설정
        case 1:
            $year += 1900; break;
        case 2:
            $genedr = "여성"; $year += 1900; break;
        case 3:
            $year += 2000; break;
        case 4:
            $genedr = "여성"; $year += 2000; break;
    }

    // 디데이 구하기
    $today = date("Ymd");                                               // 오늘 날짜
    $thisYear = substr($today,0,4);                                // 올해 구하기
    $thisMonth = substr($today,4,2);                               // 오늘은 몇 월인가?
    $thisDay = substr($today,6,2);                                 // 오늘은 몇 일인가?

    $thisBirth = (string)$thisYear. +  (string)$month. +  (string)$day;        // 올해 생일 구하기
    $birth = (string)$year. +  (string)$month. +  (string)$day;                // 생년 월일

    $birthDate = new DateTime($birth);                                     // 생년월일
    $todayDate = new DateTime($today);                                     // 오늘 날짜
    $thisBirthDate = new DateTime($thisBirth);                             // 올해 생일

    $Dday = date_diff($todayDate,$thisBirthDate);                           // 오늘부터 올해 생일까지

    // 태어나서 지금까지 몇 개월 지났는지 구함
    $BtoT = $thisYear - $year;

    if( (int)$thisMonth < (int)$month) {
        // 만 나이 구하기
        $BtoT = $BtoT -1;
    }
    $BtoT = $BtoT*12 +(int)$thisMonth-1;                                // 지금 월은 뺌

    echo $rrn ;
    echo " : ";
    echo $gender;
    echo "<br>";
    echo "유효한 주민번호 입니다.<br>";
    echo "생년월일은 : " ;
    echo $year;
    echo "년 ";
    echo $month;
    echo "월 ";
    echo $day;
    echo "일 입니다.<br>";
    echo "생일 D-day : ";
    echo $Dday->days;
    echo "일<br>";
    echo "태어난지 : ";
    echo  (int)$BtoT;
    echo "개월 되었습니다.";

} else {
    // 유효하지않은 주민등록번호
    echo "잘못된 주민번호입니다.";
}
