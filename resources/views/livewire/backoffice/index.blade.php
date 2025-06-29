<div>
    <x-header title="Dashboard" subtitle="Visão Geral do Sistema" icon="s-rectangle-group" separator />

    <div>
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Clientes</h2>
            <x-chart wire:model="chartsClientes" />
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-bold mb-4">Cobranças</h2>
            <x-chart wire:model="chartsClientes" />
        </div>
    </div>
</div>
