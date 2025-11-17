<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search - Conectando Pessoas e Serviços</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles_enhanced.css">
  <style>
    body { font-family: 'Montserrat', sans-serif; }
    .hero-bg {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      position: relative;
      overflow: hidden;
    }
    .hero-bg::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/></svg>') no-repeat center center;
      background-size: cover;
      opacity: 0.3;
    }
    .floating-card {
      animation: float 6s ease-in-out infinite;
    }
    .floating-card:nth-child(2) {
      animation-delay: -2s;
    }
    .floating-card:nth-child(3) {
      animation-delay: -4s;
    }
  </style>
</head>
<body class="bg-gray-50">

  <!-- Hero Section -->
  <div class="hero-bg min-h-screen flex items-center justify-center relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 relative z-10">
      <div class="text-center">
        
        <!-- Logo e Título Principal -->
        <div class="mb-12 animate-fade-in-up">
          <div class="inline-flex items-center justify-center w-24 h-24 bg-white/20 backdrop-blur-md rounded-3xl mb-6 border border-white/30">
            <i class="fas fa-search text-white text-4xl"></i>
          </div>
          <h1 class="text-6xl md:text-7xl font-bold text-white mb-6">
            Search
          </h1>
          <p class="text-2xl md:text-3xl text-white/90 mb-4 font-light">
            Conectando pessoas e serviços
          </p>
          <p class="text-lg text-white/80 max-w-2xl mx-auto leading-relaxed">
            A plataforma que aproxima você dos melhores prestadores de serviço da sua região. 
            Qualidade, confiança e praticidade em um só lugar.
          </p>
        </div>

        <!-- Cards de Escolha de Perfil -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-12">
          
          <!-- Card Cliente -->
          <div class="floating-card glass-morphism rounded-3xl p-8 hover-lift cursor-pointer group" onclick="window.location.href='login_cliente.php'">
            <div class="text-center">
              <div class="w-20 h-20 bg-blue-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-500/30 transition-all duration-300">
                <i class="fas fa-user text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Sou Cliente</h3>
              <p class="text-white/80 mb-6 leading-relaxed">
                Encontre os melhores prestadores de serviço para suas necessidades. 
                Avalie, compare e contrate com segurança.
              </p>
              <div class="space-y-3 mb-6">
                <div class="flex items-center text-white/90">
                  <i class="fas fa-check-circle text-green-400 mr-3"></i>
                  <span>Busca inteligente de prestadores</span>
                </div>
                <div class="flex items-center text-white/90">
                  <i class="fas fa-check-circle text-green-400 mr-3"></i>
                  <span>Avaliações e comentários reais</span>
                </div>
                <div class="flex items-center text-white/90">
                  <i class="fas fa-check-circle text-green-400 mr-3"></i>
                  <span>Contato direto e seguro</span>
                </div>
              </div>
              <button class="w-full bg-white/20 backdrop-blur-md text-white py-3 px-6 rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 border border-white/30 group-hover:scale-105">
                <i class="fas fa-sign-in-alt mr-2"></i>Entrar como Cliente
              </button>
            </div>
          </div>

          <!-- Card Prestador -->
          <div class="floating-card glass-morphism rounded-3xl p-8 hover-lift cursor-pointer group" onclick="window.location.href='login_prestador.php'">
            <div class="text-center">
              <div class="w-20 h-20 bg-green-500/20 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:bg-green-500/30 transition-all duration-300">
                <i class="fas fa-tools text-white text-3xl"></i>
              </div>
              <h3 class="text-2xl font-bold text-white mb-4">Sou Prestador</h3>
              <p class="text-white/80 mb-6 leading-relaxed">
                Ofereça seus serviços e encontre novos clientes. 
                Gerencie seu negócio e aumente sua renda.
              </p>
              <div class="space-y-3 mb-6">
                <div class="flex items-center text-white/90">
                  <i class="fas fa-check-circle text-green-400 mr-3"></i>
                  <span>Perfil profissional completo</span>
                </div>
                <div class="flex items-center text-white/90">
                  <i class="fas fa-check-circle text-green-400 mr-3"></i>
                  <span>Gestão de clientes e serviços</span>
                </div>
                <div class="flex items-center text-white/90">
                  <i class="fas fa-check-circle text-green-400 mr-3"></i>
                  <span>Sem taxas de intermediação</span>
                </div>
              </div>
              <button class="w-full bg-white/20 backdrop-blur-md text-white py-3 px-6 rounded-xl font-semibold hover:bg-white/30 transition-all duration-300 border border-white/30 group-hover:scale-105">
                <i class="fas fa-sign-in-alt mr-2"></i>Entrar como Prestador
              </button>
            </div>
          </div>
        </div>


        <!-- Call to Action Secundário -->
        <div class="text-center">
          <p class="text-white/80 mb-4">Ainda não tem uma conta?</p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="cadastro_cliente.php" class="bg-white/10 backdrop-blur-md text-white py-3 px-6 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/30">
              <i class="fas fa-user-plus mr-2"></i>Cadastrar como Cliente
            </a>
            <a href="cadastro_prestador.php" class="bg-white/10 backdrop-blur-md text-white py-3 px-6 rounded-xl font-semibold hover:bg-white/20 transition-all duration-300 border border-white/30">
              <i class="fas fa-briefcase mr-2"></i>Cadastrar como Prestador
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Elementos Decorativos -->
    <div class="absolute top-20 left-10 w-20 h-20 bg-white/10 rounded-full animate-pulse"></div>
    <div class="absolute bottom-20 right-10 w-16 h-16 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
    <div class="absolute top-1/2 left-20 w-12 h-12 bg-white/10 rounded-full animate-pulse" style="animation-delay: 2s;"></div>
  </div>



  <!-- JavaScript -->
  <script>
    // Animações de entrada
    document.addEventListener('DOMContentLoaded', function() {
      const cards = document.querySelectorAll('.floating-card');
      cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.2}s`;
      });
    });

    // Efeito de parallax suave
    window.addEventListener('scroll', function() {
      const scrolled = window.pageYOffset;
      const parallax = document.querySelector('.hero-bg');
      const speed = scrolled * 0.5;
      parallax.style.transform = `translateY(${speed}px)`;
    });

    // Smooth scroll para links internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });
  </script>

</body>
</html>

