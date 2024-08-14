<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('name')
                ->required()
                ->maxlength(255),

                Forms\Components\TextInput::make('phone_number')
                ->required()
                ->maxlength(255),

                Forms\Components\TextInput::make('trx_id')
                ->required()
                ->maxlength(255),

                Forms\Components\TextArea::make('address')
                ->required()
                ->maxlength(1024),

                Forms\Components\TextInput::make('total_amount')
                ->required()
                ->numeric()
                ->prefix('IDR'),

                Forms\Components\TextInput::make('duration')
                ->required()
                ->numeric()
                ->prefix('Days')
                ->maxValue(255),

                Forms\Components\Select::make('product_id')
                ->relationship('product','name')
                ->searchable()
                ->preload()
                ->required(),

                Forms\Components\Select::make('store_id')
                ->relationship('store','name')
                ->searchable()
                ->preload()
                ->required(),

                Forms\Components\DatePicker::make('started_at')
                ->required(),

                Forms\Components\DatePicker::make('ended_at')
                ->required(),

                Forms\Components\Select::make('delivery_type')
                ->options(
                    [
                        'pickup' => 'Pickup Store',
                        'home_delivery' => 'Home Delivery',
                    ]
                )->required(),

                Forms\Components\FileUpload::make('proof')
                ->required()
                ->openable()
                ->image(),

                Forms\Components\Select::make('is_paid')
                ->options(
                    [
                        '1' => 'Paid',
                        '0' => 'Not Paid',
                    ]
                )->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                ->searchable(),
                Tables\Columns\TextColumn::make('trx_id')
                ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                ->numeric()
                ->prefix('Rp '),
                Tables\Columns\IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->label('Sudah Bayar?'),

                Tables\Columns\TextColumn::make('product.name'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
}
