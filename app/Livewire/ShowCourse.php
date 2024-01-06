<?php

namespace App\Livewire;

use App\Models\Course;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Fieldset;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Concerns\InteractsWithInfolists;
use Filament\Infolists\Contracts\HasInfolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;

class ShowCourse extends Component implements HasInfolists, HasForms
{
    use InteractsWithInfolists, InteractsWithForms;
    public Course $course;

    public function mount(Course $course)
    {
        $this->course = $course;
        $this->course->loadCount('episodes');
    }

    public function courseInfolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->record($this->course)
            ->schema([
                Section::make([
                    TextEntry::make('title')
                        ->label('')
                        ->size('text-4xl')
                        ->weight('font-bold')
                        ->columnSpanFull(),

                    TextEntry::make('tagline')
                        ->label('')
                        ->columnSpanFull(),

                    TextEntry::make('instructor.name')
                        ->label('Your teacher')
                        ->columnSpanFull(),

                    Fieldset::make('')
                        ->columns(3)
                        ->columnSpan(1)
                        ->schema([

                            TextEntry::make('episodes_count')
                                ->label('')
                                ->formatStateUsing(fn ($state) =>  "$state episodes")
                                ->icon('heroicon-o-film'),

                            TextEntry::make('formatted_length')
                                ->label('')
                                ->icon('heroicon-o-clock'),

                            TextEntry::make('created_at')
                                ->label('')
                                ->formatStateUsing(fn ($state) => $state->diffForHumans())
                                ->icon('heroicon-o-calendar'),

                        ])
                        ->extraAttributes(['class' => 'border-none !p-0']),
                ])->columns(2),

                Section::make('About this course')
                    ->description(fn (Course $record) => $record->description)
                    ->aside()
                    ->schema([

                        RepeatableEntry::make('episodes')
                            ->schema([
                                TextEntry::make('title')
                                    ->label('')
                                    ->icon('heroicon-o-play-circle'),

                                TextEntry::make('formatted_Length')
                                    ->label('')
                                    ->icon('heroicon-o-clock'),
                            ])
                            ->columns(2),

                    ])->columns(1),
            ]);
    }

    public function render()
    {
        return view('livewire.show-course');
    }
}
