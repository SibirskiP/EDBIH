<p class="font-light text-gray-500 sm:text-xl dark:text-gray-400 text-center">
    cijena</p>
<br>
<div class="flex flex-col items-center">

    <input type="range" min="0" max="100" value="{{ request('cijena', 40) }}" class="range range-primary" id="cijena" name="cijena" />
    <span id="selected-value">{{ request('cijena', 40) }}</span>
</div>

<script>
    const slider = document.getElementById('cijena');
    const selectedValue = document.getElementById('selected-value');

    slider.addEventListener('input', () => {
        selectedValue.textContent = slider.value;
    });
</script>
