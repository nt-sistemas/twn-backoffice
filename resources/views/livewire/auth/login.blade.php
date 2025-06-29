<div class="w-full max-w-md mx-auto mt-8">
    <x-card>
        <div>
            <div class="flex flex-col items-center mb-6">
                <x-icon name="s-cube" class="w-16 h-16 mx-auto mb-4 text-gray-500" />
                <h2 class="text-3xl font-black text-primary">TWN - Backoffice</h2>
            </div>
            <h1 class="text-xl font-bold text-center mb-4">Área Restrita</h1>
            <p class="text-center text-gray-600 mb-6">Entre com suas credenciais para acessar o sistema.</p>
        </div>
        <x-form wire:submit="login">
            <x-input label="E-mail:" wire:model="email" placeholder="Digite seu E-mail" />
            <x-input label="Senha:" type="password" wire:model="password" placeholder="Digite sua senha" />

            <x-slot:actions>

                <x-button label="Acessar" class="btn-primary w-full" type="submit" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>

</div>
