<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
  private $schedules = [
    ['date' => '2023-11-30', 'time' => '15:00', 'remainingSeats' => 4],
    ['date' => '2023-11-30', 'time' => '20:00', 'remainingSeats' => 6],
    ['date' => '2023-12-01', 'time' => '15:00', 'remainingSeats' => 1],
    ['date' => '2023-12-01', 'time' => '20:00', 'remainingSeats' => 2],
    ['date' => '2023-12-02', 'time' => '15:00', 'remainingSeats' => 4],
    ['date' => '2023-12-02', 'time' => '20:00', 'remainingSeats' => 5],
    ['date' => '2024-07-15', 'time' => '13:00', 'remainingSeats' => 5],
    ['date' => '2024-07-17', 'time' => '15:00', 'remainingSeats' => 2],
    ['date' => '2024-07-17', 'time' => '21:00', 'remainingSeats' => 2],
    ['date' => '2024-07-16', 'time' => '21:00', 'remainingSeats' => 2],
    ['date' => '2024-07-11', 'time' => '21:00', 'remainingSeats' => 2],
    ['date' => '2024-08-01', 'time' => '15:00', 'remainingSeats' => 1],
  ];

  public function getBestSchedule(Request $request)
  {
    $currentDateTime = $request->input('currentDate');
    $participants = $request->input('ages'); // array of ages

    $bestOption = $this->calculateBestOption($currentDateTime, $participants);

    if ($bestOption) {
      return response()->json($bestOption);
    } else {
      return response()->json(['message' => '利用可能な上映スケジュールがありません。'], 404);
    }
  }

  private function calculateBestOption($currentDateTime, $participants)
  {
    $currentDateTime = new \DateTime($currentDateTime);
    $bestOption = null;
    $minTotalPrice = PHP_INT_MAX;

    // スケジュールを日付と時間でソート
    usort($this->schedules, function ($a, $b) {
      $dateTimeA = new \DateTime("{$a['date']} {$a['time']}");
      $dateTimeB = new \DateTime("{$b['date']} {$b['time']}");
      return $dateTimeA <=> $dateTimeB;
    });

    foreach ($this->schedules as $schedule) {
      $scheduleDateTime = new \DateTime("{$schedule['date']} {$schedule['time']}");
      // これより前のスケジュールはスキップ
      if ($scheduleDateTime < $currentDateTime) {
        continue;
      }

      $totalPrice = $this->calculateTotalPrice($participants, $schedule);
      //totalPrice !== null：無効なスケジュールの除外
      //totalPrice < $minTotalPrice：検討中のスケジュールの料金がこれまで見つかった最安値よりも小さいかどうか
      if ($totalPrice !== null && $totalPrice < $minTotalPrice && $schedule['remainingSeats'] >= count($participants)) {
        $minTotalPrice = $totalPrice;
        $bestOption = [
          'date' => $schedule['date'],
          'time' => $schedule['time'],
          'price' => $totalPrice
        ];
      }
    }

    return $bestOption;
  }

  private function calculateTotalPrice($participants, $schedule)
  {
    $basePrices = [
      'adult' => 1600,
      'minor' => 1000
    ];

    $discountPrices = [
      'firstDay' => 1000,
      'lateShow' => 1400,
      'senior' => 1200
    ];

    $totalPrice = 0;
    foreach ($participants as $age) {
      $isMinor = $age < 18;
      $isSenior = $age >= 60;

      $price = $isMinor ? $basePrices['minor'] : $basePrices['adult'];

      // Apply discounts
      $scheduleDate = new \DateTime($schedule['date']);
      // ファーストデイ割引を毎月1日に適用
      if ($scheduleDate->format('j') == 1) {
        $price = min($price, $discountPrices['firstDay']);
      }
      if ($schedule['time'] >= '20:00' && !$isMinor) {
        $price = min($price, $discountPrices['lateShow']);
      }
      //(new \DateTime($schedule['date']))->format('N') < 6) ~平日かどうか~
      if ($isSenior && $schedule['time'] < '20:00' && (new \DateTime($schedule['date']))->format('N') < 6) {
        $price = min($price, $discountPrices['senior']);
      }

      // 未成年が含まれる場合は、20:00以降は入場できないようにする。
      if ($isMinor && $schedule['time'] >= '20:00') {
        return null;
      }

      $totalPrice += $price;
    }

    return $totalPrice;
  }
}
