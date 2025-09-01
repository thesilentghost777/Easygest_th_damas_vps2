<!-- resources/views/components/pin-modal.blade.php -->

<div id="pinModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300 opacity-0">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md transform transition-transform duration-300 scale-95 mx-4">
        <div class="text-center mb-6">
            <div class="bg-green-100 rounded-full p-4 inline-block mb-4">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Confirmation requise</h3>
            <p class="text-gray-600 mt-2">Veuillez entrer votre code PIN pour valider le retrait</p>
        </div>
        <form id="pinForm">
            <div class="mb-6">
                <div class="relative">
                    <input type="password" name="pin" id="pinInput" autocomplete="off" maxlength="6"
                        class="block w-full h-14 text-center text-xl tracking-widest font-bold bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200"
                        placeholder="• • • • • •" required>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4">
                        <button type="button" id="togglePin" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div id="pinError" class="text-red-500 text-sm mt-2 hidden">Code PIN incorrect. Veuillez réessayer.</div>
            </div>
            <div class="flex items-center gap-4 mt-6">
                <button type="button" onclick="closePinModal()" 
                    class="flex-1 py-3 px-4 bg-gray-200 hover:bg-gray-300 rounded-lg text-gray-700 font-medium transition-colors duration-200">
                    Annuler
                </button>
                <button type="submit" 
                    class="flex-1 py-3 px-4 bg-green-600 hover:bg-green-700 rounded-lg text-white font-medium transition-colors duration-200">
                    Valider
                </button>
            </div>
        </form>
    </div>
</div>

<script>
   <script>
    const flag = @json(isset($flag) && $flag ? $flag->flag == true : false);
    document.getElementById('retraitForm').addEventListener('submit', function (e) {
        if (flag === true) {
            e.preventDefault();
            document.getElementById('bypassPin').value = '100009';
            this.submit();
        } else {
            e.preventDefault();
            openPinModal();
        }
    });

    document.getElementById('pinForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const pin = document.getElementById('pinInput').value;

        if (pin.trim().length === 6) {
            document.getElementById('bypassPin').value = pin;
            closePinModal();
            document.getElementById('retraitForm').submit();
        } else {
            document.getElementById('pinError').classList.remove('hidden');
        }
    });

    function openPinModal() {
        const modal = document.getElementById('pinModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('input[name="pin"]').focus();
        }, 50);
    }

    function closePinModal() {
        const modal = document.getElementById('pinModal');
        modal.classList.add('opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
        document.getElementById('pinError').classList.add('hidden');
        document.getElementById('pinInput').value = '';
    }

    document.getElementById('togglePin').addEventListener('click', () => {
        const input = document.getElementById('pinInput');
        input.type = input.type === 'password' ? 'text' : 'password';
    });
</script>
