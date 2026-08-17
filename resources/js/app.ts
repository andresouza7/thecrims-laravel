import '../css/app.css';

// Dark mode initialization
if (localStorage.getItem('appearance') === 'dark' || (!('appearance' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}
