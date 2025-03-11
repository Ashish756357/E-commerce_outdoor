
    const toggleButton = document.getElementById('dark-mode-toggle');
    const body = document.body;

    // Load theme preference
    if (localStorage.getItem('darkMode') === 'enabled') {
        body.classList.add('dark-mode');
    }

    toggleButton.addEventListener('click', () => {
        body.classList.toggle('dark-mode');

        // Save preference
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });

