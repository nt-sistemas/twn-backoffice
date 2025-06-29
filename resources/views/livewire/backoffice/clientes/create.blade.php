<div class="flex flex-col gap-4">
    <x-header title="Novo Cliente" subtitle="Criação de Cliente" icon="s-building-office-2" separator />

    <div class="flex flex-col gap-8 w-full bg-white p-4 rounded-lg shadow-md">
        <form wire:submit.prevent="save" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Nome/Razão Social" wire:model.defer="nome_razao_social" required />
                <x-select label="Tipo" wire:model.defer="tipo" :options="$tipoOptions" required />
                <x-input label="Nome Fantasia" wire:model.defer="nome_fantasia" />
                <x-input label="CPF/CNPJ" wire:model.defer="cpf_cnpj" required />
                <x-input label="Telefone" wire:model.defer="telefone" required />
                <x-input label="Email" type="email" wire:model.defer="email" required />
                <x-input label="Endereço" wire:model.defer="endereco" required />
                <x-select label="Tipo de Contrato" wire:model.defer="tipo_contrato" :options="$tipoContratoOptions" required />
                <x-input label="Valor do Contrato" type="number" step="0.01" wire:model.defer="valor_contrato"
                    required />
                <x-input label="Desconto" type="number" step="0.01" wire:model.defer="valor_desconto" />
                <x-input label="Data de Início" type="date" wire:model.defer="data_inicio_contrato" required />
                <x-input label="Data de Término" type="date" wire:model.defer="data_fim_contrato" />
                <x-input label="ID Sistema" type="text" wire:model.defer="id_gestor" required />
                <x-select label="Status" wire:model.defer="status" :options="$statusOptions" required />

            </div>
            <x-textarea label="Observações" wire:model.defer="observacoes" rows="4" />
            <div class="flex justify-end">
                <x-button type="submit" icon="o-check" label="Salvar" class="btn-primary btn-sm" />
            </div>
        </form>


    </div>



</div>
