function initLoginCadastro() {
  const login = document.querySelector('[data-form ="login"]');
  const cadastro = document.querySelector('[data-form ="cadastro"]');
  
  const btnCadastro = document.querySelector('[data-form ="login"] .botoes a');
  const btnLogin = document.querySelector('[data-form ="cadastro"] .botoes a');
  
  login.classList.add('form-ativo'); 
  
  function ativaLogin(event) {
    event.preventDefault();
    cadastro.classList.remove('form-ativo'); 
    login.classList.add('form-ativo');
  }
  
  function ativaCadastro(event) {
    event.preventDefault();
    login.classList.remove('form-ativo');
    cadastro.classList.add('form-ativo'); 
  }
  
  
  btnCadastro.addEventListener('click', ativaCadastro);
  btnLogin.addEventListener('click', ativaLogin);
}

initLoginCadastro();

function acionaErro() {
  const locationIndex = window.location.href.split('?');

  if (locationIndex.length > 1) {
    const tipoErro = locationIndex[1].split('=')[1];
    
    if (tipoErro == "cadastro") {
      const login = document.querySelector('[data-form ="login"]');
      const cadastro = document.querySelector('[data-form ="cadastro"]');
      
      login.classList.remove('form-ativo');
      cadastro.classList.add('form-ativo'); 
    }
  }
}
acionaErro();

document.addEventListener("DOMContentLoaded", () => {
  const themeToggle = document.getElementById("theme-toggle");
  const body = document.body;

  // Verifica o tema armazenado no localStorage
  const currentTheme = localStorage.getItem("theme");
  if (currentTheme === "dark") {
    body.classList.add("dark-mode");
    themeToggle.innerHTML = '<i class="fa fa-sun-o"></i>';
  }

  // Alterna entre os temas ao clicar no botão
  themeToggle.addEventListener("click", (event) => {
    event.preventDefault(); // Impede qualquer comportamento inesperado
    event.stopPropagation(); // Evita propagação para elementos pais
    body.classList.toggle("dark-mode");

    if (body.classList.contains("dark-mode")) {
      localStorage.setItem("theme", "dark");
      themeToggle.innerHTML = '<i class="fa fa-sun-o"></i>';
    } else {
      localStorage.setItem("theme", "light");
      themeToggle.innerHTML = '<i class="fa fa-moon-o"></i>';
    }
  });
});

