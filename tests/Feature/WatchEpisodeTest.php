<?php
use App\Livewire\WatchEpisode;
use App\Models\Course;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;

it('renders successfully', function () {
    $course = Course::factory()
        ->for(User::factory()->instructor(), 'instructor')
        ->has(Episode::factory()->state(['vimeo_id' => 123456789]), 'episodes')
        ->create();

    Livewire::test(WatchEpisode::class, ['course' => $course])
        ->assertStatus(200);
});

it('shows the first episode if none is provided', function () {
    // Arrange
    $course = Course::factory()
        ->for(User::factory()->instructor(), 'instructor')
        ->has(Episode::factory(2)->state(new Sequence(
            ['overview' => 'First episode overview'],
            ['overview' => 'Second episode overview'],
        )), 'episodes')
        ->create();

    // Act & Assert
    Livewire::test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->assertSeeText($course->episodes->first()->overview);
});

it('shows the provided episode', function () {
    // Arrange
    $course = Course::factory()
        ->for(User::factory()->instructor(), 'instructor')
        ->has(Episode::factory(2)->state(new Sequence(
            ['overview' => 'First episode overview'],
            ['overview' => 'Second episode overview'],
        )), 'episodes')
        ->create();

    // Act & Assert
    Livewire::test(WatchEpisode::class, ['course' => $course, 'episode' => $course->episodes->last()])
        ->assertOk()
        ->assertSeeText('Second episode overview');
});

it('shows the list of episode', function() {
      //Arrange
      $course = Course::factory()
      ->for(User::factory()->instructor(), 'instructor')
      ->has(
          Episode::factory()
              ->count(3)
              ->state(
                  new Sequence(
                      ['title' => 'First Episode'],
                      ['title' => 'Second Episode'],
                      ['title' => 'Third Episode'],
                  )
              )
      )
      ->create();

  Livewire::test(WatchEpisode::class, ['course' => $course])
      ->assertOk()
     ->assertSeeInOrder([
        'First Episode',
        'Second Episode',
        'Third Episode',
     ]);
});

it('shows the video player', function() {
    $course = Course::factory()
        ->for(User::factory()->instructor(), 'instructor')
        ->has(Episode::factory()->state(['vimeo_id' => 123456789]), 'episodes')
        ->create();

    Livewire::test(WatchEpisode::class, ['course' => $course])
    ->assertOk()
    ->assertSee('<iframe src="https://player.vimeo.com/video/123456789"', false);
});
