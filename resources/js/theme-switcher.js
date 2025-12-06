/**
 * resources/js/theme-switcher.js
 * Menangani perubahan tema tanpa reload dan menyimpannya ke DB.
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Baca meta tag dari DB
    const metaTheme = document.querySelector('meta[name="user-theme"]');
    const savedTheme = metaTheme ? metaTheme.content : 'system-blue';

    // 2. Terapkan saat load
    applyTheme(savedTheme);

    // 3. Set status input radio di halaman profil
    const [mode, color] = savedTheme.split('-');
    const modeInput = document.querySelector(`input[name="theme_mode"][value="${mode}"]`);
    const colorInput = document.querySelector(`input[name="theme_color"][value="${color}"]`);
    if(modeInput) modeInput.checked = true;
    if(colorInput) colorInput.checked = true;

    // 4. Listener Perubahan Input
    const inputs = document.querySelectorAll('input[name="theme_mode"], input[name="theme_color"]');
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            const selMode = document.querySelector('input[name="theme_mode"]:checked').value;
            const selColor = document.querySelector('input[name="theme_color"]:checked').value;
            
            // Update UI Langsung
            applyTheme(`${selMode}-${selColor}`);

            // Simpan ke DB
            fetch('/user/update-theme', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ mode: selMode, color: selColor })
            });
        });
    });
});

function applyTheme(themeStr) {
    if(!themeStr) return;
    const [mode, color] = themeStr.split('-');
    const root = document.documentElement;

    root.setAttribute('data-accent', color);

    if(mode === 'system') {
        const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        root.setAttribute('data-theme', isDark ? 'dark' : 'light');
    } else {
        root.setAttribute('data-theme', mode);
    }
}