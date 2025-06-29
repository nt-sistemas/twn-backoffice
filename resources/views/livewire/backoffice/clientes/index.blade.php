<div>
    <x-header title="Clientes" subtitle="Lista de Clientes" icon="s-building-office-2" />

    <div class="flex gap-4 mb-4">
        <x-stat title="Ativos" :value="$ativos" icon="s-building-office-2" color="text-green-500" />

        <x-stat title="Pendente" :value="$pendentes" icon="s-building-office-2" color="text-orange-500" />

        <x-stat title="Inativos" :value="$inativos" icon="s-building-office-2" color="text-red-500" />
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
            @scope('cell_valor_total', $cliente)
                <span class="font-bold">R$ {{ number_format($cliente->valor_total, 2, ',', '.') }}</span>
            @endscope

            @scope('cell_tipo_contrato', $cliente)
                @if ($cliente->tipo_contrato == 'mensal')
                    <span class="badge badge-primary w-full font-bold text-white">Mensal</span>
                @elseif($cliente->tipo_contrato == 'anual')
                    <span class="badge badge-secondary w-full font-bold text-white">Anual</span>
                @else
                    <span class="badge badge-info w-full font-bold text-white">Demonstração</span>
                @endif
            @endscope

            @scope('cell_status', $cliente)
                @if ($cliente->status == 'ativo')
                    <span class="badge badge-success w-full font-bold text-white">Ativo</span>
                @elseif($cliente->status == 'pendente')
                    <span class="badge badge-warning w-full font-bold text-white">Pendente</span>
                @else
                    <span class="badge badge-error w-full font-bold text-white">Inativo</span>
                @endif
            @endscope

            @scope('actions', $cliente)
                <div class="flex">
                    <x-button icon="s-eye" class="btn-circle btn-ghost btn-xs"
                        wire:click="showClientesDrawer({{ $cliente->id }})" tooltip="Visualizar Cliente" />
                    <x-button icon="s-pencil" class="btn-circle btn-ghost btn-xs"
                        link="{{ route('clientes.edit', $cliente->id) }}" tooltip="Editar Cliente" />
                    <x-button icon="s-trash" class="btn-circle btn-ghost btn-xs"
                        wire:click="openModalDelete({{ $cliente->id }})" tooltip="Excluir Cliente" />
                </div>
            @endscope
        </x-table>
    </div>

    {{-- Right --}}
    <x-drawer wire:model="showDrawer" class="w-11/12 lg:w-1/3" right>
        <x-button icon="s-x-mark" class="mb-4" @click="$wire.showDrawer = false" />

        <h2 class="text-xl font-bold mb-4">
            {{ $cliente->nome_razao_social ?? 'Detalhes do Cliente' }}
        </h2>
        <x-menu-separator />

        @if ($cliente)
            <div>
                <x-list-item :item="$cliente" value="nome_razao_social" sub-value="email" no-separator no-hover
                    class="-mx-2 !-my-2 rounded">
                    <x-slot:actions>
                        <x-button icon="s-pencil" class="btn-circle btn-ghost btn-xs"
                            wire:navigate="/clientes/{{ $cliente->id }}/edit" tooltip-left="Editar Cliente" />
                    </x-slot:actions>
                </x-list-item>

                <x-menu-separator />

                <div class="flex flex-col gap-2">
                    <p><strong>CPF/CNPJ:</strong> {{ $cliente->cpf_cnpj }}</p>
                    <p><strong>Telefone:</strong> {{ $cliente->telefone }}</p>
                    <p><strong>Status:</strong>
                        @if ($cliente->status === 'pendente')
                            <span class="text-orange-500 font-bold">Pendente</span>
                        @elseif($cliente->status === 'inativo')
                            <span class="text-red-500 font-bold">Inativo</span>
                        @else
                            <span class="text-green-500 font-bold">Ativo</span>
                        @endif
                    </p>
                    <p><strong>Email:</strong> {{ $cliente->email }}</p>
                    <p><strong>Endereço:</strong> {{ $cliente->endereco }}</p>
                </div>
                <x-menu-separator />
                <div class="flex flex-col gap-2">
                    <p><strong>Valor do Contrato:</strong>R$ {{ number_format($cliente->valor_contrato, 2, ',', '.') }}
                    </p>

                    <p><strong>Observações:</strong></p>
                    <p>{{ $cliente->observacoes ?? 'Nenhuma observação registrada.' }}</p>
                </div>
                <x-menu-separator />
                <div class="flex flex-col gap-2">
                    <p><strong>Criado em:</strong> {{ $cliente->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Atualizado em:</strong> {{ $cliente->updated_at->format('d/m/Y H:i') }}</p>

                </div>


            </div>
        @endif
    </x-drawer>

    <x-modal wire:model="confirmDelete" title="Excluir: {{ $cliente->nome_razao_social ?? '' }}"
        class="text-red-500 backdrop-blur">

        <p class="text-black font-bold">Você tem certeza que deseja excluir este cliente?</p>
        <p class="font-bold italic">Esta ação não pode ser desfeita.</p>
        <x-slot:actions>
            <x-button label="Cancelar" class="bg-gray-300 btn-sm" @click="$wire.confirmDelete = false" />
            <x-button label="Excluir" class="btn-error btn-sm" wire:click="deleteCliente({{ $cliente->id ?? '' }})" />
        </x-slot:actions>
    </x-modal>
</div>
