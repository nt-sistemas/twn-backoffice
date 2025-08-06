<?php

namespace App\Services;

use App\Jobs\SendNFSe;
use App\Models\City;
use App\Models\Customer;
use App\Models\InvoiceType;
use App\Models\Transmission;
use GuzzleHttp\Psr7\Request;

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

        if ($transmissionExists) {
            return redirect()->route('filament.app.resources.invoices.edit', $data['id'])
                ->with('error', 'Transmission already exists for this invoice.');
        }


        $transmission = new Transmission();
        $transmission->invoice_id = $data['id'];
        $transmission->customer_id = $data['customer_id'];
        $transmission->amount = $data['amount'];
        $transmission->status = 'transmitting';
        $transmission->transmission_date = $data['paid_date'];
        $transmission->save();

        $ibgeCode = City::where('name', $customer->city)
            ->where('state', $customer->state)
            ->first();



        if (!$ibgeCode) {
            return redirect()->route('filament.app.resources.invoices.edit', $data['id'])
                ->with('error', 'City not found for the provided customer details.');
        }

        // Prepare data for the job

        $sendData = [
            'id' => $data['id'],
            'customer_id' => $customer->id,
            'razao_social' => $customer->name,
            'document' => str_replace(['.', '/', '-'], '', $customer->document),
            'amount' => $data['amount'],
            'paid_date' => $data['paid_date'],
            'ibge_code' => $ibgeCode->ibge_code,
            'descriptions' => $type_invoice->name . ' - ' . $data['reference'],
            'address' => $customer->address,
            'number' => $customer->number,
            'complement' => $customer->complement,
            'neighborhood' => $customer->neighborhood,
            'city' => $customer->city,
            'state' => $customer->state,
            'postal_code' => str_replace(['.', '/', '-'], '', $customer->postal_code),
        ];


        SendNFSe::dispatch($sendData)
            ->delay(now()->addSeconds(5));

        return redirect()->route('filament.app.resources.transmissions.index');


        // $request = new Request('POST', 'https://novohamburgo.atende.net/?pg=services&service=WNENotaFiscalEletronicaNfe&wsdl', $headers, $body);
        // $res = $client->sendAsync($request)->wait();
        // echo $res->getBody();
    }
}
