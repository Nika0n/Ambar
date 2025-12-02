document.addEventListener('DOMContentLoaded', function() {
    const formCadastro = document.querySelector('form');

    if (formCadastro) {
        formCadastro.addEventListener('submit', function(evento) {
            const nome = document.getElementById('nome').value.trim();
            if (nome === "") {
                alert("O campo Nome da Espécie é obrigatório.");
                evento.preventDefault();
                return;
            }

            const curiosidades = document.getElementById('curiosidades').value.trim();
            if (curiosidades.length < 20) {
                alert("A descrição (Curiosidades) deve ter pelo menos 20 caracteres para ser informativa.");
                evento.preventDefault();
                return;
            }
            
            console.log("Formulário validado com sucesso.");
        });
    }

    const filtroDieta = document.getElementById('filtro_dieta');
    const filtroPeriodo = document.getElementById('filtro_periodo');
    const formFiltro = filtroDieta ? filtroDieta.closest('form') : null;

    if (formFiltro) {
        filtroDieta.addEventListener('change', function() {
            formFiltro.submit();
        });
        filtroPeriodo.addEventListener('change', function() {
            formFiltro.submit();
        });
    }
});