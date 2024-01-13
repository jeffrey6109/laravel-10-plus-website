<?php

namespace App\Livewire;

use App\Infolists\Components\VideoPlayerEntry;
use App\Models\Course;
use App\Models\Episode;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Livewire\Component;

class WatchEpisode extends Component implements HasInfolists, HasForms
{
    use InteractsWithInfolists, InteractsWithForms;

    public Course $course;
    public Episode $currentEpisode;

    public function mount(Course $course, Episode $episode)
    {
        $this->course = $course;

        if (isset($episode->id)) {
            $this->currentEpisode = $episode;
        } else {
            $this->currentEpisode = $course->episodes->first();
        }
    }

    public function episodeInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->currentEpisode)
            ->schema([
                Section::make([

                    VideoPlayerEntry::class::make('vimeo_id')
                        ->hiddenLabel(),

                    TextEntry::make('overview')
                        ->hiddenLabel()
                        ->size('text-4xl')
                        ->weight('font-bold')
                        ->columnSpanFull(),

                    RepeatableEntry::make('course.episodes')
                        ->schema([
                            TextEntry::make('title')
                                ->hiddenLabel()
                                ->icon('heroicon-o-play-circle'),

                            TextEntry::make('formatted_Length')
                                ->hiddenLabel()
                                ->icon('heroicon-o-clock'),
                        ])
                        ->columns(2),

                ])->columnSpanFull(),
            ]);
    }

    public function render()
    {
        return view('livewire.watch-episode');
    }
}
