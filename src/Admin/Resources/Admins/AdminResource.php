<?php

namespace SmartCms\Kit\Admin\Resources\Admins;

use SmartCms\Kit\Admin\Resources\Admins\Pages\CreateAdmin;
use SmartCms\Kit\Admin\Resources\Admins\Pages\EditAdmin;
use SmartCms\Kit\Admin\Resources\Admins\Pages\ListAdmins;
use SmartCms\Kit\Admin\Resources\Admins\Schemas\AdminForm;
use SmartCms\Kit\Admin\Resources\Admins\Tables\AdminsTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use SmartCms\Kit\Models\Admin;

class AdminResource extends Resource
{
    protected static ?string $model = Admin::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function getNavigationGroup(): ?string
    {
        return __('kit::admin.system');
    }

    public static function form(Schema $schema): Schema
    {
        return AdminForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdminsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdmins::route('/'),
            'create' => CreateAdmin::route('/create'),
            'edit' => EditAdmin::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return Auth::user()->id == 1;
    }

    public static function canEdit(Model $record): bool
    {
        /**
         * @var Admin|null $user
         */
        $user = Auth::user();
        if (!$user || $user->id == 1 || $record->id == 1) {
            return false;
        }
        return true;
    }

    public static function canDelete(Model $record): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return $record->id !== 1;
    }
}
