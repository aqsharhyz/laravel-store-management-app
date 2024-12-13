<?php

namespace App\Filament\Pages;

use App\Jobs\CustomNotificationJob;
use App\Jobs\ScheduledMassNotificationJob;
use App\Models\User;
use App\Notifications\CustomBroadcastNotification;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Livewire\Attributes\Validate;

class Notifications extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.notifications';

    public static function canAccess(): bool
    {
        //!
        // return auth()->user()->role === 'admin';
        return true;
    }

    public $data = [];

    protected function rules()
    {
        return [
            'data' => [
                'required',
                'array',
            ],
            'data.title' => [
                'required',
                'string',
                'max:255',
            ],
            'data.message' => [
                'required',
                'string',
                'min:5',
            ],
            'data.date' => [
                'nullable',
                'date',
                'after_or_equal:now',
            ],
            'data.users_id' => [
                'nullable',
                'array',
            ],
            'data.users_id.*' => [
                // 'required',
                'exists:users,id',
            ],
        ];
    }

    // #[Validate('required|min:5|max:255')]
    // public $notificationTitle = '';

    public function save()
    {
        dd($this->form->getState());

        $this->validate();

        $this->data['date'] ??= now()->addSeconds(5);
        $this->data['users_id'] ??= null;

        ScheduledMassNotificationJob::dispatch($this->data['title'], $this->data['message'], $this->data['users_id'])
            ->delay($this->data['date']);

        Notification::make()
            ->title('Saved successfully')
            ->success()
            ->send();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                // Forms\Components\DateTimePicker::make('date')
                //     ->native(false)
                //     //!
                //     ->default(now())
                //     ->seconds(false)
                //     ->minDate(now()),
                Forms\Components\Select::make('users_id')
                    ->multiple()
                    // ->options(User::all()->pluck('name', 'id')->toArray())
                    ->options([
                        '1' => 'User 1',
                        '2' => 'User 2',
                        '3' => 'User 3',
                    ])
                    // ->searchable()
                    // ->preload()
                    ->columnSpanFull(),
                // dd(User::all()->pluck('name', 'id')->toArray()),
            ])
            ->statePath('data');
    }
}
