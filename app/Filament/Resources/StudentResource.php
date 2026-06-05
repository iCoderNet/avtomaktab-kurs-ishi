<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = "O'quvchilar";
    protected static ?string $modelLabel = "O'quvchi";
    protected static ?string $pluralModelLabel = "O'quvchilar";
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Section::make("O'quvchi ma'lumotlari")->schema([
                Forms\Components\TextInput::make('user.name')->label('Ism Familiya')->required(),
                Forms\Components\TextInput::make('user.email')->label('Email')->email()->required(),
                Forms\Components\TextInput::make('user.phone')->label('Telefon'),
                Forms\Components\Select::make('license_category')->label('Toifa')
                    ->options(['A'=>'A','B'=>'B','C'=>'C','D'=>'D'])->default('B'),
                Forms\Components\Select::make('status')->label('Holat')
                    ->options(['active'=>'Faol','inactive'=>'Nofaol','completed'=>'Tugatdi','expelled'=>'Chiqarildi'])
                    ->default('active'),
                Forms\Components\DatePicker::make('enrollment_date')->label("Ro'yxatga olingan"),
                Forms\Components\TextInput::make('total_fee')->label("Jami to'lov")->numeric(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_number')->label('Raqam')
                    ->searchable()->copyable()->fontFamily('mono'),
                Tables\Columns\TextColumn::make('user.name')->label('Ism')
                    ->searchable()->sortable()
                    ->getStateUsing(fn($record) => $record->user?->name ?? '—')
                    ->description(fn($record) => $record->user?->phone),
                Tables\Columns\TextColumn::make('license_category')->label('Toifa')
                    ->badge()->color('primary'),
                Tables\Columns\TextColumn::make('instructor.user.name')->label('Instruktor')
                    ->getStateUsing(fn($record) => $record->instructor?->user?->name ?? '—'),
                Tables\Columns\TextColumn::make('total_fee')->label("Jami to'lov")
                    ->money('UZS')->sortable(),
                Tables\Columns\TextColumn::make('paid_amount')->label("To'langan")
                    ->money('UZS'),
                Tables\Columns\TextColumn::make('status')->label('Holat')
                    ->badge()
                    ->color(fn($state) => match($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'completed' => 'primary',
                        'expelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match($state) {
                        'active' => 'Faol', 'inactive' => 'Nofaol',
                        'completed' => 'Tugatdi', 'expelled' => 'Chiqarildi',
                        default => $state
                    }),
                Tables\Columns\TextColumn::make('enrollment_date')->label('Sanasi')
                    ->date('d.m.Y')->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->label('Holat')->options([
                    'active' => 'Faol', 'inactive' => 'Nofaol',
                    'completed' => 'Tugatdi', 'expelled' => 'Chiqarildi',
                ]),
                SelectFilter::make('license_category')->label('Toifa')
                    ->options(['A'=>'A','B'=>'B','C'=>'C','D'=>'D']),
            ])
            ->recordActions([
                EditAction::make()->label('Tahrirlash'),
                DeleteAction::make()->label("O'chirish"),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label("O'chirish"),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }
}
