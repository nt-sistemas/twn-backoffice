<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\InvoiceType;
use App\Models\Transmission;
use GuzzleHttp\Client;

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

        $client = new Client;
        $headers = [
            'Content-Type' => 'application/xml',
            'Authorization' => 'Basic NDczODcyMjcwMDAxMDM6QFR3bjM4NzIyNw==',
            'Cookie' => 'PHPSESSID=ssojn0u0raq7tn6q9lih29tp83; cidade=padrao',
        ];
        $body = '<nfse>
        <nfse_teste>1</nfse_teste>
	<nf>
        <valor_total>'.$data['amount'].'</valor_total>
		<valor_desconto>0,00</valor_desconto>
		<valor_ir>0,00</valor_ir>
		<valor_inss>0,00</valor_inss>
		<valor_contribuicao_social>0,00</valor_contribuicao_social>
		<valor_rps>0,00</valor_rps>
		<valor_pis>0,00</valor_pis>
		<valor_cofins>0,00</valor_cofins>
		<observacao>0</observacao>
	</nf>
	<prestador>
		<cpfcnpj>47387227000103</cpfcnpj>
		<cidade>8771</cidade>
	</prestador>
	<tomador>
		<tipo>J</tipo>
		<cpfcnpj>'.$customer->document.'</cpfcnpj>
		<ie></ie>
		<sobrenome_nome_fantasia>'.$customer->name.'</sobrenome_nome_fantasia>
		<nome_razao_social>'.$customer->name.'</nome_razao_social>
	</tomador>
	<itens>
		<lista>
			<codigo_local_prestacao_servico>8771</codigo_local_prestacao_servico>
			<codigo_atividade>3089</codigo_atividade>
			<codigo_item_lista_servico>105</codigo_item_lista_servico>
			<descritivo>'.$type_invoice->name.' - '.$data['reference'].'</descritivo>
			<aliquota_item_lista_servico>2,1700</aliquota_item_lista_servico>
			<situacao_tributaria>0</situacao_tributaria>
			<valor_tributavel>'.$data['amount'].'</valor_tributavel>
			<valor_deducao>0,00</valor_deducao>
			<valor_issrf>0,00</valor_issrf>
			<valor_desconto_incondicional>0,00</valor_desconto_incondicional>
			<tributa_municipio_prestador>S</tributa_municipio_prestador>
		</lista>
	</itens>
</nfse>
';

        $transmission = new Transmission;
        $transmission->invoice_id = $data['id'];
        $transmission->customer_id = $data['customer_id'];
        $transmission->amount = $data['amount'];
        $transmission->status = 'transmitting';
        $transmission->save();

        return redirect()->route('filament.app.resources.transmissions.index');

        // dd($body);
        // $request = new Request('POST', 'https://novohamburgo.atende.net/?pg=services&service=WNENotaFiscalEletronicaNfe&wsdl', $headers, $body);
        // $res = $client->sendAsync($request)->wait();
        // echo $res->getBody();

    }
}
