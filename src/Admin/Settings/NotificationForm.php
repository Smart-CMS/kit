<?php

namespace SmartCms\Kit\Admin\Settings;

use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs\Tab;
use SmartCms\Kit\Notifications\TestNotification;
use SmartCms\Kit\Models\Admin;

class NotificationForm
{
    public static function make(): Tab
    {
        return Tab::make(__('kit::admin.notifications'))->schema([
            Section::make(__('kit::admin.email'))
                ->headerActions([
                    Action::make('test_notification')
                        ->label(__('kit::admin.test_notification'))
                        ->icon('heroicon-o-envelope')
                        ->action(function () {
                            try {
                                /**
                                 * @var Admin $user
                                 */
                                $user = auth()->user();
                                Admin::query()->find($user->id)->notifyNow(new TestNotification('mail'));
                                Notification::make()
                                    ->title(__('kit::admin.test_notification_sent'))
                                    ->success()
                                    ->send();
                            } catch (Exception $e) {
                                Notification::make()
                                    ->title(__('kit::admin.test_notification_error'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])
                ->schema([
                    TextInput::make('mail.from')
                        ->label(__('kit::admin.mail_from_email')),
                    TextInput::make('mail.name')
                        ->label(__('kit::admin.mail_from_name')),
                    Select::make('mail.provider')
                        ->label(__('kit::admin.mail_provider'))
                        ->options([
                            'smtp' => 'SMTP',
                            'sendmail' => 'Sendmail',
                        ])
                        ->formatStateUsing(function ($state) {
                            return $state ?? 'sendmail';
                        })
                        ->live()
                        ->default('sendmail'),
                    TextInput::make('mail.host')
                        ->label(__('kit::admin.mail_host'))
                        ->hidden(function ($get) {
                            return $get('mail.provider') != 'smtp';
                        })->required(),
                    TextInput::make('mail.port')
                        ->label(__('kit::admin.mail_port'))
                        ->hidden(function ($get) {
                            return $get('mail.provider') != 'smtp';
                        })->required(),
                    TextInput::make('mail.username')
                        ->label(__('kit::admin.mail_username'))
                        ->hidden(function ($get) {
                            return $get('mail.provider') != 'smtp';
                        })->required(),
                    TextInput::make('mail.password')
                        ->label(__('kit::admin.mail_password'))
                        ->password()
                        ->revealable(false)
                        ->hidden(function ($get) {
                            return $get('mail.provider') != 'smtp';
                        })->required(),
                    Select::make('mail.encryption')
                        ->label(__('kit::admin.mail_encryption'))
                        ->options([
                            'ssl' => 'SSL',
                            'tls' => 'TLS',
                        ])
                        ->hidden(function ($get) {
                            return $get('mail.provider') != 'smtp';
                        })->required(),
                ])->collapsible(),
            Section::make(__('kit::admin.telegram'))->schema([
                TextInput::make('telegram.token')
                    ->label(__('kit::admin.bot_token'))
                    ->password()
                    ->revealable(false)->readOnly(fn($get, $state) => ! $get('is_token_deleted') || $state)->suffixAction(Action::make('delete_token')->icon('heroicon-o-trash')->action(function ($set) {
                        $set('is_token_deleted', true);
                        $set('telegram.token', null);
                    })),
                TextInput::make('telegram.bot_username')
                    ->label(__('kit::admin.bot_username'))
                    ->required(function ($state) {
                        return strlen($state) > 0;
                    }),
            ])->collapsible()->headerActions([
                Action::make('test_notification')
                    ->label(__('kit::admin.test_notification'))
                    ->icon('heroicon-o-envelope')
                    ->action(function () {
                        /**
                         * @var Admin $user
                         */
                        $user = auth()->user();
                        if (! $user->telegram_id) {
                            Notification::make()
                                ->title(__('kit::admin.you_dont_have_telegram_id'))
                                ->danger()
                                ->send();

                            return;
                        }
                        try {
                            $user->notifyNow(new TestNotification('telegram'));
                            Notification::make()
                                ->title(__('kit::admin.test_notification_sent'))
                                ->success()
                                ->send();
                        } catch (Exception $e) {
                            Notification::make()
                                ->title(__('kit::admin.test_notification_error'))
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })->disabled(function ($get) {
                        return ! $get('telegram.token') || ! $get('telegram.bot_username');
                    }),
            ]),
        ]);
    }
}
