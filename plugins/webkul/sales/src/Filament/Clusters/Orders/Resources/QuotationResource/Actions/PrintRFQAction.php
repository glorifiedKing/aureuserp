<?php

namespace Webkul\Sale\Filament\Clusters\Orders\Resources\QuotationResource\Actions;

use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Livewire\Component;
use Webkul\Purchase\Enums\OrderState;
use Webkul\Purchase\Models\Order;

class PrintRFQAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'purchases.orders.print-rfq';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label(__('purchases::filament/clusters/orders/resources/order/actions/print-rfq.label'))
            ->action(function (Order $record, Component $livewire) {
                $pdf = PDF::loadView('purchases::filament.clusters.orders.orders.actions.print-quotation', [
                    'records'  => [$record],
                ]);

                $pdf->setPaper('a4', 'portrait');

                $record->update([
                    'state' => OrderState::SENT,
                ]);

                $record->lines->each(function ($line) {
                    $line->update([
                        'state' => OrderState::SENT,
                    ]);
                });

                $livewire->updateForm();

                return response()->streamDownload(function () use ($pdf) {
                    echo $pdf->output();
                }, 'Purchase Order-'.str_replace('/', '_', $record->name).'.pdf');
            })
            ->color(fn (): string => $this->getRecord()->state === OrderState::DRAFT ? 'primary' : 'gray')
            ->visible(fn () => in_array($this->getRecord()->state, [
                OrderState::DRAFT,
                OrderState::SENT,
            ]));
    }
}
