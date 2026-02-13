<?php

namespace App\Services;

use App\Jobs\SendNFSe;
use App\Models\City;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceType;
use App\Models\Transmission;
use Filament\Notifications\Notification;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class NotaFiscalService
{
    public function __construct()
    {
        // Constructor logic if needed
    }

    public function sendIntegration(array $data)
    {
        $customer = Customer::where('id', $data['customer_id'])->first();
        $type_invoice = InvoiceType::where('id', $data['invoice_type_id'])->first();

        $transmissionExists = Transmission::where('invoice_id', $data['id'])
            ->where('customer_id', $data['customer_id'])
            ->where('amount', $data['amount'])
            ->where('transmission_date', $data['paid_date'])
            ->exists();

        $ibgeCode = City::where('name', $customer->city)
            ->where('state', $customer->state)
            ->first();

        if (! $ibgeCode) {
            Notification::make()
                ->title('Cidade IBGE ')
                ->body('A cidade e estado fornecidos para o cliente não correspondem a nenhum registro em nosso banco de dados. Por favor, verifique os detalhes do cliente e tente novamente.')
                ->color('danger')
                ->send();
        }

        if ($transmissionExists) {
            Notification::make()
                ->title('Já existe um envio de Nota para esta fatura')
                ->body('Uma transmissão com os mesmos dados já foi criada para esta fatura. Verifique os detalhes e tente novamente.')
                ->color('danger')
                ->send();

            return redirect()->route('filament.app.resources.invoices.edit', $data['id']);
        }

        $transmission = new Transmission;
        $transmission->invoice_id = $data['id'];
        $transmission->customer_id = $data['customer_id'];
        $transmission->amount = $data['amount'];
        $transmission->status = 'transmitting';
        $transmission->transmission_date = $data['paid_date'];
        $transmission->save();

        // Prepare data for the job

        $sendData = [
            'id' => $data['id'],
            'customer_id' => $customer->id,
            'razao_social' => $customer->name,
            'document' => str_replace(['.', '/', '-'], '', $customer->document),
            'amount' => $data['amount'],
            'paid_date' => $data['paid_date'],
            'ibge_code' => $ibgeCode->ibge_code,
            'descriptions' => $type_invoice->name.' - '.$data['reference'],
            'address' => $customer->address,
            'number' => $customer->number,
            'complement' => $customer->complement,
            'neighborhood' => $customer->neighborhood,
            'city' => $customer->city,
            'state' => $customer->state,
            'postal_code' => str_replace(['.', '/', '-'], '', $customer->postal_code),
        ];

        Log::info('Sending NFSe with data to QUEUE: '.json_encode($sendData));

        SendNFSe::dispatch($sendData)->delay(now()->addSeconds(5));

        return redirect()->route('filament.app.resources.transmissions.index');

        // $request = new Request('POST', 'https://novohamburgo.atende.net/?pg=services&service=WNENotaFiscalEletronicaNfe&wsdl', $headers, $body);
        // $res = $client->sendAsync($request)->wait();
        // echo $res->getBody();
    }

    public function resendIntegration($transmissionId)
    {
        $transmission = Transmission::find($transmissionId);

        if (! $transmission) {
            Notification::make()
                ->title('Transmissão não encontrada')
                ->body('A transmissão que você está tentando reenviar não foi encontrada. Por favor, verifique os detalhes e tente novamente.')
                ->color('danger')
                ->send();

            return redirect()->route('filament.app.resources.transmissions.index');
        }

        $customer = Customer::where('id', $transmission->customer_id)->first();
        $invoice = Invoice::where('id', $transmission->invoice_id)->first();

        $data = [
            'id' => $transmission->invoice_id,
            'customer_id' => $transmission->customer_id,
            'amount' => $transmission->amount,
            'paid_date' => $transmission->transmission_date,
        ];

        $transmission->update(
            [
                'status' => 'transmitting',
                'response_code' => null,
                'response_message' => null,
                'attempts' => $transmission->attempts + 1,
            ]
        );
        $sendData = [
            'id' => $data['id'],
            'customer_id' => $customer->id,
            'razao_social' => $customer->name,
            'document' => str_replace(['.', '/', '-'], '', $customer->document),
            'amount' => $data['amount'],
            'paid_date' => $data['paid_date'],
            'ibge_code' => $this->getIbgeCode($customer->city, $customer->state),
            'descriptions' => $invoice->invoiceType->name.' - '.$invoice->reference,
            'address' => $customer->address,
            'number' => $customer->number,
            'complement' => $customer->complement,
            'neighborhood' => $customer->neighborhood,
            'city' => $customer->city,
            'state' => $customer->state,
            'postal_code' => str_replace(['.', '/', '-'], '', $customer->postal_code),
        ];

        Log::info('Reenviando NFSe com dados para QUEUE: '.json_encode($sendData));

        SendNFSe::dispatch($sendData)->delay(now()->addSeconds(5));

        return redirect()->route('filament.app.resources.transmissions.index');
    }

    public function getIbgeCode($city, $state)
    {
        $ibgeCode = City::where('name', $city)
            ->where('state', $state)
            ->first();

        return $ibgeCode ? $ibgeCode->ibge_code : null;
    }
}
