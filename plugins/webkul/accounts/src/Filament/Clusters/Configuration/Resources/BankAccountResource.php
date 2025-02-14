<?php

namespace Webkul\Account\Filament\Clusters\Configuration\Resources;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Webkul\Invoice\Filament\Clusters\Configuration;
use Webkul\Invoice\Filament\Clusters\Configuration\Resources\BankAccountResource\Pages;
use Webkul\Contact\Filament\Clusters\Configurations\Resources\BankAccountResource as BaseBankAccountResource;

class BankAccountResource extends BaseBankAccountResource
{
    protected static ?string $cluster = Configuration::class;

    protected static bool $shouldRegisterNavigation = false;

    public static function form(Form $form): Form
    {
        $form = BaseBankAccountResource::form($form);

        $components = collect($form->getComponents())->forget(1)->all();

        $form->components($components);

        return $form;
    }

    public static function table(Table $table): Table
    {
        $table = BaseBankAccountResource::table($table);

        $components = collect($table->getColumns())->forget('can_send_money')->all();

        $table->columns($components);

        return $table;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankAccounts::route('/'),
        ];
    }
}
