<?php

namespace App\Livewire;

use App\Models\Course;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
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
                    TextEntry::make('title'),
                    TextEntry::make('tagline'),
                    TextEntry::make('description'),
                    TextEntry::make('instructor.name'),
                    TextEntry::make('episodes_count')
                        ->label('')
                        ->formatStateUsing(fn ($state) =>  "$state episodes"),
                    TextEntry::make('created_at')
                        ->date('M d, Y'),
                ])
            ]);
    }

    public function render()
    {
        return view('livewire.show-course');
    }
}
