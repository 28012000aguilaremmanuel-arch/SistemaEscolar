// Sistema Escolar - Funcionalidades JavaScript
document.addEventListener('DOMContentLoaded', function() {
    
    // Validación en tiempo real para CURP
    const curpInput = document.querySelector('input[name="curp"]');
    if (curpInput) {
        curpInput.addEventListener('input', function(e) {
            let value = e.target.value.toUpperCase();
            // Formato básico CURP (18 caracteres)
            value = value.replace(/[^A-Z0-9]/g, '');
            if (value.length > 18) value = value.slice(0, 18);
            e.target.value = value;
            
            // Validación visual
            if (value.length === 18) {
                e.target.style.borderColor = '#28a745';
            } else {
                e.target.style.borderColor = '#dc3545';
            }
        });
    }
    
    // Validación calificaciones (0-10)
    const califInput = document.querySelector('input[name="calificacion"]');
    if (califInput) {
        califInput.addEventListener('input', function(e) {
            let value = parseFloat(e.target.value);
            if (value > 10) e.target.value = 10;
            if (value < 0) e.target.value = 0;
        });
    }
    
    // Confirmación antes de cerrar sesión
    const logoutLinks = document.querySelectorAll('.btn-logout');
    logoutLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            if (!confirm('¿Estás seguro de que quieres cerrar sesión?')) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-focus en primer input
    const firstInput = document.querySelector('input[type="text"], input[type="email"], input[type="password"], select');
    if (firstInput) {
        firstInput.focus();
    }
    
    // Mejora visual para selects
    const selects = document.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('focus', function() {
            this.style.borderColor = '#667eea';
        });
        select.addEventListener('blur', function() {
            this.style.borderColor = '#e1e5e9';
        });
    });
    
    // Mensajes de éxito/deserror con auto-desvanecimiento
    const successMsg = document.querySelector('.success');
    const errorMsg = document.querySelector('.error');
    
    if (successMsg) {
        setTimeout(() => {
            successMsg.style.opacity = '0';
            setTimeout(() => successMsg.remove(), 300);
        }, 4000);
    }
    
    if (errorMsg) {
        setTimeout(() => {
            errorMsg.style.opacity = '0';
            setTimeout(() => errorMsg.remove(), 300);
        }, 5000);
    }
    
    // Responsive para tablets/móviles
    function adjustForMobile() {
        if (window.innerWidth <= 768) {
            const tables = document.querySelectorAll('table');
            tables.forEach(table => {
                table.classList.add('mobile-table');
            });
        }
    }
    
    adjustForMobile();
    window.addEventListener('resize', adjustForMobile);
    
    // PWA - Guardar en pantalla de inicio
    let deferredPrompt;
    window.addEventListener('beforeinstallprompt', (e) => {
        deferredPrompt = e;
    });
    
    // Buscador rápido en listas largas (para admin)
    const searchInputs = document.querySelectorAll('.search-students');
    searchInputs.forEach(input => {
        input.addEventListener('input', function() {
            const rows = input.closest('table').querySelectorAll('tbody tr');
            const term = this.value.toLowerCase();
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    });
});

// Funciones utilitarias
function formatCURP(curp) {
    return curp.toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 18);
}

function calculatePromedio(calificaciones) {
    const sum = calificaciones.reduce((a, b) => a + parseFloat(b), 0);
    return (sum / calificaciones.length).toFixed(1);
}

// Notificaciones toast
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '1';
        toast.style.transform = 'translateX(0)';
    }, 100);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(300px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Exportar datos (para admin)
function exportToCSV() {
    const table = document.querySelector('table');
    if (!table) return;
    
    let csv = [];
    table.querySelectorAll('tr').forEach(row => {
        const cols = row.querySelectorAll('td, th');
        const rowData = Array.from(cols).map(col => col.textContent.trim());
        csv.push(rowData.join(','));
    });
    
    const csvContent = 'data:text/csv;charset=utf-8,' + csv.join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'estudiantes.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}