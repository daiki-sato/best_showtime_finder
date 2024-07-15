<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;

class ScheduleControllerTest extends TestCase
{
  /**
   * テストのセットアップ
   */
  protected function setUp(): void
  {
    parent::setUp();

    $this->app->instance(\App\Http\Controllers\ScheduleController::class, new class extends \App\Http\Controllers\ScheduleController
    {
      protected $schedules = [
        ['date' => '2023-11-30', 'time' => '15:00', 'remainingSeats' => 4],
        ['date' => '2023-11-30', 'time' => '20:00', 'remainingSeats' => 6],
        ['date' => '2023-12-01', 'time' => '15:00', 'remainingSeats' => 1],
        ['date' => '2023-12-01', 'time' => '20:00', 'remainingSeats' => 2],
        ['date' => '2023-12-02', 'time' => '15:00', 'remainingSeats' => 4],
        ['date' => '2023-12-02', 'time' => '20:00', 'remainingSeats' => 5],
        ['date' => '2024-07-15', 'time' => '13:00', 'remainingSeats' => 5],
        ['date' => '2024-07-17', 'time' => '15:00', 'remainingSeats' => 2],
        ['date' => '2024-07-17', 'time' => '21:00', 'remainingSeats' => 2],
      ];
    });
  }

  /**
   * @test
   */
  public function it_returns_the_best_schedule()
  {
    $response = $this->postJson('/api/optimal-schedule', [
      'currentDate' => '2024-07-15T13:00:00',
      'ages' => [25, 30, 18]
    ]);

    $response->assertStatus(200);
    $response->assertJson([
      'date' => '2024-07-15',
      'time' => '13:00',
      'price' => 4800
    ]);
  }

  /**
   * @test
   */
  public function it_returns_no_schedule_message_if_no_valid_schedule_exists()
  {
    $response = $this->postJson('/api/optimal-schedule', [
      'currentDate' => '2025-01-01T00:00:00',
      'ages' => [25, 30, 18]
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => '利用可能な上映スケジュールがありません。'
    ]);
  }

  /**
   * @test
   */
  public function it_applies_first_day_discount()
  {
    $response = $this->postJson('/api/optimal-schedule', [
      'currentDate' => '2023-12-01T10:00:00',
      'ages' => [25]
    ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
      'date' => '2023-12-01',
      'price' => 1000
    ]);
  }

  /**
   * @test
   */
  public function it_does_not_allow_minors_to_attend_late_shows()
  {
    $response = $this->postJson('/api/optimal-schedule', [
      'currentDate' => '2023-11-30T21:00:00',
      'ages' => [17]
    ]);

    $response->assertStatus(404);
    $response->assertJson([
      'message' => '利用可能な上映スケジュールがありません。'
    ]);
  }

  /**
   * @test
   */
  public function it_applies_senior_discount_on_weekdays_before_20()
  {
    $response = $this->postJson('/api/optimal-schedule', [
      'currentDate' => '2023-11-30T10:00:00',
      'ages' => [65]
    ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
      'date' => '2023-11-30',
      'time' => '15:00',
      'price' => 1200
    ]);
  }

  /**
   * @test
   */
  public function it_chooses_the_earliest_schedule_if_multiple_have_same_price()
  {
    $response = $this->postJson('/api/optimal-schedule', [
      'currentDate' => '2024-07-15T10:00:00',
      'ages' => [25]
    ]);

    $response->assertStatus(200);
    $response->assertJsonFragment([
      'date' => '2024-07-15',
      'time' => '13:00',
      'price' => 1600
    ]);
  }
}
