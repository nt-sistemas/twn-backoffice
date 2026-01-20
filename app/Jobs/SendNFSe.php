<?php

namespace App\Jobs;

use App\Models\Transmission;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendNFSe implements ShouldQueue
{
    use Queueable;

    // Timeout after 120 seconds
    public $data = null;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        log::info('Sending NFSe with data: ' . json_encode($this->data));
        $this->sendIntegration();

        //$transmission->status = 'transmitted';
        //$transmission->response_code = '200'; // Example response code
        //$transmission->response_message = 'Transmission successful'; // Example response message
        //$transmission->save();
    }

    public function sendIntegration()
    {
        // Logic to send the NFSe
        // This could involve calling an external API or processing the data in some way
        //,
        $client = new Client(['auth' => ['47387227000103', '@Twn387227']]);
        $headers = [
            'Content-Type' => 'application/xml',
            'Authorization' => ['Basic ' . base64_encode('47387227000103:@Twn387227')],
            'Cookie' => 'PHPSESSID=m7umnriutdjlho78jrg4qtkr55; cidade=padrao',
        ];



        $body = '
        <soapenv:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:net="net.atende">
   <soapenv:Header/>
   <soapenv:Body>
      <GerarNfseEnvio>
<Rps>
<InfDeclaracaoPrestacaoServico xmlns="http://www.abrasf.org.br/nfse.xsd" Id="RPS_1">
<Competencia>' . $this->data['paid_date'] . '</Competencia> 
<Servico>
<Valores>
<ValorServicos>' . $this->data['amount'] . '</ValorServicos>
<ValorDeducoes>0</ValorDeducoes>
<ValorPis>00.00</ValorPis>
<ValorCofins>00.00</ValorCofins>
<ValorInss>00.00</ValorInss>
<ValorIr>00.00</ValorIr>
<ValorCsll>00.00</ValorCsll>
<OutrasRetencoes>00.00</OutrasRetencoes>
<Aliquota>2.1700</Aliquota>
</Valores>
<IssRetido>2</IssRetido>
<ItemListaServico>01.05</ItemListaServico>
<CodigoCnae>3089</CodigoCnae>
<!--<CodigoTributacaoMunicipio>14.01</CodigoTributacaoMunicipio>-->
<Discriminacao>' . $this->data['descriptions'] . '</Discriminacao>
<CodigoMunicipio>4313409</CodigoMunicipio>
<ExigibilidadeISS>1</ExigibilidadeISS>
<MunicipioIncidencia>4313409</MunicipioIncidencia>		                       
</Servico>
<Prestador>
<CpfCnpj>
<Cnpj>47387227000103</Cnpj>
</CpfCnpj>
</Prestador>
<TomadorServico>
<IdentificacaoTomador>
<CpfCnpj>
<!--<Cnpj>63513983000180</Cnpj>-->
<Cnpj>' . $this->data['document'] . '</Cnpj>
</CpfCnpj>
</IdentificacaoTomador>
<RazaoSocial>' . $this->data['razao_social'] . '</RazaoSocial>
<Endereco>
<Endereco>' . $this->data['address'] . '</Endereco>
<Numero>' . $this->data['number'] . '</Numero>' . PHP_EOL .
(isset($this->data['complement']) && $this->data['complement'] !== null ? '<Complemento>' . $this->data['complement'] . '</Complemento>' : '') . '
<Bairro>' . $this->data['neighborhood'] . '</Bairro>
<CodigoMunicipio>' . $this->data['ibge_code'] . '</CodigoMunicipio>
<Uf>' . $this->data['state'] . '</Uf>
<Cep>' . $this->data['postal_code'] . '</Cep>
</Endereco> 
</TomadorServico>
<RegimeEspecialTributacao>1</RegimeEspecialTributacao>
<OptanteSimplesNacional>1</OptanteSimplesNacional>
<IncentivoFiscal>2</IncentivoFiscal>
</InfDeclaracaoPrestacaoServico>
<Signature xmlns="http://www.w3.org/2000/09/xmldsig#">
<SignedInfo>
<CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" />
<SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1" />
<Reference URI="#R1">
<Transforms>
<Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature" />
<Transform Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315" />
</Transforms>
<DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1" />
<DigestValue>SPiPQZk/MC0rfgztPD3UmlCYkN8=</DigestValue>
</Reference>
</SignedInfo>
<SignatureValue>aYCjQY3hlJSw8b2w/EpiaeZSrKdJ6mVW1xW+s5R7AY5MLXaA/mBQnkftl6COgwEM9keHtOYvqR8Yys25IfY21ZGaE/ow2LHoMZTdb06xsJl1C9mmBXSqfihK9pDKrhZPklza9nbSxOVIJNbMKLszbZRM9sJHFIE6Hc7Oy3s6+Qc=</SignatureValue>
<KeyInfo>
<X509Data>
<X509Certificate>MIIGvzCCBaegAwIBAgIQH/nrIzJA/Hhhy3nmQ3HfPTANBgkqhkiG9w0BAQUFADB4MQswCQYDVQQGEwJCUjETMBEGA1UEChMKSUNQLUJyYXNpbDE2MDQGA1UECxMtU2VjcmV0YXJpYSBkYSBSZWNlaXRhIEZlZGVyYWwgZG8gQnJhc2lsIC0gUkZCMRwwGgYDVQQDExNBQyBDZXJ0aXNpZ24gUkZCIEczMB4XDTA5MDcwNzAwMDAwMFoXDTEyMDcwNTIzNTk1OVowggEdMQswCQYDVQQGEwJCUjELMAkGA1UECBMCUkoxFzAVBgNVBAcUDlJJTyBERSBKQU5FSVJPMRMwEQYDVQQKFApJQ1AtQnJhc2lsMTYwNAYDVQQLFC1TZWNyZXRhcmlhIGRhIFJlY2VpdGEgRmVkZXJhbCBkbyBCcmFzaWwgLSBSRkIxFjAUBgNVBAsUDVJGQiBlLUNOUEogQTMxODA2BgNVBAsUL0F1dGVudGljYWRvIHBvciBDZXJ0aXNpZ24gQ2VydGlmaWNhZG9yYSBEaWdpdGFsMUkwRwYDVQQDE0BUSVBMQU4gQ09OU1VMVE9SSUEgRSBTRVJWSUNPUyBFTSBJTkZPUk1BVElDQSBMVERBOjA0NjQyNTU0MDAwMTQzMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDU8w4/Qow0FkUaHboNcqDwmGyyl+5xDuhZ8c5+yF4GTLfVnUvjL9mnsCJ1sGSZmJ8A26en4XChAAKbcfxocQEMp3PtyQDejsZnNrW7pxxxGz4n1b8MylWJVvfSdM3aQ2JvKQSXKPfl02FELVDF1uF16ItXb78MOEWJA8wtUGNRwIDAQABo4IDIDCCAxwwgbUGA1UdEQSBrTCBqqA9BgVgTAEDBKA0BDIyNDA3MTk3NjA3MTM4NTM3Nzg2MDAwMDAwMDAwMDAwMDAwMDAwOTI5OTA2MjFpZnBSSqAfBgVgTAEDAqAWBBRGRVJOQU5ETyBTSUxWQSBCUkFHQaAZBgVgTAEDA6AQBA4wNDY0MjU1NDAwMDE0M6AXBgVgTAEDB6AOBAwwMDAwMDAwMDAwMDCBFGZicmFnYUB0aXBsYW4uY29tLmJyMAkGA1UdEwQCMAAwHwYDVR0jBBgwFoAU/IBr1U3R/HjYbGQvYUs4p4Lw3J0wDgYDVR0PAQH/BAQDAgXgMIIBEAYDVR0fBIIBBzCCAQMwV6BVoFOGUWh0dHA6Ly9pY3AtYnJhc2lsLmNlcnRpc2lnbi5jb20uYnIvcmVwb3NpdG9yaW8vbGNyL0FDQ2VydGlzaWduUkZCRzMvTGF0ZXN0Q1JMLmNybDBWoFSgUoZQaHR0cDovL2ljcC1icmFzaWwub3V0cmFsY3IuY29tLmJyL3JlcG9zaXRvcmlvL2xjci9BQ0NlcnRpc2lnblJGQkczL0xhdGVzdENSTC5jcmwwUKBOoEyGSmh0dHA6Ly9yZXBvc2l0b3Jpby5pY3BicmFzaWwuZ292LmJyL2xjci9SRkIvQUNDZXJ0aXNpZ25SRkJHMy9MYXRlc3RDUkwuY3JsMFUGA1UdIAROMEwwSgYGYEwBAgMGMEAwPgYIKwYBBQUHAgEWMmh0dHA6Ly9pY3AtYnJhc2lsLmNlcnRpc2lnbi5jb20uYnIvcmVwb3NpdG9yaW8vZHBjMB0GA1UdJQQWMBQGCCsGAQUFBwMEBggrBgEFBQcDAjCBmwYIKwYBBQUHAQEEgY4wgYswKAYIKwYBBQUHMAGGHGh0dHA6Ly9vY3NwLmNlcnRpc2lnbi5jb20uYnIwXwYIKwYBBQUHMAKGU2h0dHA6Ly9pY3AtYnJhc2lsLmNlcnRpc2lnbi5jb20uYnIvcmVwb3NpdG9yaW8vY2VydGlmaWNhZG9zL0FDX0NlcnRpc2lnbl9SRkJfRzMucDdjMA0GCSqGSIb3DQEBBQUAA4IBAQA3ki6qGqXHbSbsZOVOjP5SXdPG3hXjr2wfshnqcGzIrc3flhymx4kVr6v+K7LJ7KAqM48dv2vEyoNxqOSEnkBxk/8vYvhtC5uiHTwkXmgn0kHqhVXEsYSjBqAokqQ36A5PiaBBAWFmdSzm2/CrLbpZXdiaqt89KXamC6Atlkszqe30W0QldOXG8N0EHr1C2FbmVf/JUUt9semSnLRavuHJDox3I/U8adl0+EgIP8uxWghkcOmo+hrwrpLsu7/FBwLmPToktQpz/YbxsGspaGlbchJtaxdBhCgXaRuvfgQ5+33KlpZvaj8VMfAoPgs3yAqb7Ir/3cNaPFfwBkUtt5KC</X509Certificate>
</X509Data>
</KeyInfo>
</Signature>
</Rps>
</GerarNfseEnvio>
</soapenv:Body>
</soapenv:Envelope>
';


        $request = new Request('POST', 'https://novohamburgo.atende.net/?pg=services&service=WNENotaFiscalEletronicaNfe', $headers, $body);
        $res = $client->sendAsync($request)->wait();
        if ($res->getStatusCode() !== 200) {
            throw new \Exception('Failed to send NFSe: ' . $res->getBody());
        }
        // Update the transmission status and response

        Log::info('NFSe sent successfully' . (string) $res->getBody()->getContents());

        $transmission = Transmission::where('invoice_id', $this->data['id'])
            ->where('customer_id', $this->data['customer_id'])
            ->where('amount', $this->data['amount'])
            ->where('transmission_date', $this->data['paid_date'])
            ->first();


        $transmission->status = 'transmitted';
        $transmission->response_code = 200;
        $transmission->response_message = $res->getBody()->getContents();
        $transmission->save();
    }
}
