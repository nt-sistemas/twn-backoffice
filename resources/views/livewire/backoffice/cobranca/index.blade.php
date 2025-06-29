<div>
    <x-header title="Cobranças" subtitle="Controle de Cobranças" icon="s-currency-dollar" separator />

    <div class="flex gap-4 mb-4">
        <x-stat title="Pagas" :value="$pagas" icon="s-currency-dollar" color="text-green-500" />

        <x-stat title="Pendente" :value="$pendentes" icon="s-currency-dollar" color="text-orange-500" />

        <x-stat title="Atrasados" :value="$canceladas" icon="s-currency-dollar" color="text-red-500" />
    </div>

    <div class="flex flex-col gap-8 w-full bg-white p-4 rounded-lg shadow-md">
        <div class="flex gap-4 items-center mb-4 w-full">
            <div class="w-full">
                <x-input type="text" placeholder="Pesquisar por Nome, Razão Social, Fantasia, Documento ou E-mail"
                    wire:model.live="search" class="w-full" />

            </div>

            <x-button icon="o-plus" label="Novo Cliente" class="btn-primary btn-sm float-right"
                link="{{ route('clientes.create') }}" tooltip="Novo Cliente" />
        </div>


        <x-table :headers="$this->headers()" :rows="$this->getData()" with-pagination>

            @scope('cell_valor', $cobranca)
                <span class="font-bold text-xs">R$ {{ number_format($cobranca->valor, 2, ',', '.') }}</span>
            @endscope
            @scope('cell_status', $cobranca)
                @if ($cobranca->status == 'pendente')
                    <span class="badge badge-warning w-full font-bold text-white">Pendente</span>
                @elseif($cobranca->status == 'pago')
                    <span class="badge badge-success w-full font-bold text-white">Paga</span>
                @else
                    <span class="badge badge-error w-full font-bold text-white">Atrasado</span>
                @endif
            @endscope
            @scope('cell_data_vencimento', $cobranca)
                <span
                    class="font-bold text-xs">{{ Carbon\Carbon::parse($cobranca->data_vencimento)->format('d/m/Y') }}</span>
            @endscope
            @scope('cell_cliente.nome_razao_social', $cobranca)
                <span class="font-bold text-xs">{{ $cobranca->cliente->nome_razao_social ?? 'N/A' }}</span>
            @endscope

            @scope('actions', $cobranca)
                <div class="flex">
                    <x-button icon="s-eye" class="btn-circle btn-ghost btn-xs" tooltip="Visualizar Cliente" />
                    <x-button icon="s-pencil" class="btn-circle btn-ghost btn-xs" tooltip="Editar Cliente" />
                    <x-button icon="s-trash" class="btn-circle btn-ghost btn-xs" tooltip="Excluir Cliente" />
                </div>
            @endscope
        </x-table>
    </div> {{-- The whole world belongs to you. --}}
</div>
