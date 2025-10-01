# Search - Sistema de Gerenciamento e Busca de Prestadores de Serviço

## 📋 Descrição do Projeto

O **Search** é uma plataforma web moderna que conecta clientes a prestadores de serviço de forma eficiente e segura. O sistema oferece duas interfaces distintas: uma para clientes que buscam serviços e outra para prestadores que desejam oferecer seus serviços.

## ✨ Funcionalidades Principais

### Para Clientes
- **Tela de Login Dedicada**: Interface específica para clientes com design intuitivo
- **Home com Grid de Prestadores**: Visualização em cards dos prestadores disponíveis
- **Busca Inteligente**: Sistema de busca por serviço, profissional ou localização
- **Filtros Avançados**: Filtros por categoria, localização e avaliação
- **Detalhes do Prestador**: Página completa com informações detalhadas do prestador
- **Sistema de Avaliações**: Visualização de avaliações e comentários de outros clientes

### Para Prestadores
- **Tela de Login Específica**: Interface dedicada para prestadores de serviço
- **Perfil Profissional**: Gestão completa do perfil e serviços oferecidos
- **Gestão de Disponibilidade**: Controle de horários e disponibilidade

## 🎨 Design e Experiência do Usuário

### Características Visuais
- **Design Moderno**: Interface limpa e profissional usando Tailwind CSS
- **Responsivo**: Totalmente adaptado para desktop, tablet e mobile
- **Animações Suaves**: Transições e micro-interações para melhor UX
- **Gradientes e Efeitos**: Visual moderno com glass morphism e gradientes
- **Tipografia**: Fonte Montserrat para melhor legibilidade

### Paleta de Cores
- **Azul Principal**: #3B82F6 (Confiança e profissionalismo)
- **Verde Secundário**: #22C55E (Sucesso e disponibilidade)
- **Roxo Accent**: #8B5CF6 (Modernidade e inovação)
- **Cinza Neutro**: #6B7280 (Textos e elementos secundários)

## 📁 Estrutura de Arquivos

```
TCC-Search/
├── pags/
│   ├── index.php                 # Página inicial com seleção de perfil
│   ├── login_cliente.php         # Login específico para clientes
│   ├── login_prestador.php       # Login específico para prestadores
│   ├── home_cliente.php          # Home do cliente com grid de prestadores
│   ├── detalhes_prestador.php    # Página de detalhes do prestador
│   ├── styles_enhanced.css       # Estilos CSS aprimorados
│   ├── connection.php            # Conexão com banco de dados
│   ├── navbar.php               # Componente de navegação
│   ├── logout.php               # Logout do sistema
│   ├── cadastro.php             # Cadastro geral (existente)
│   ├── prestadores.php          # Lista de prestadores (existente)
│   └── processa_*.php           # Scripts de processamento
└── uploads/                     # Diretório de imagens dos prestadores
```

## 🚀 Tecnologias Utilizadas

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla)
- **Framework CSS**: Tailwind CSS
- **Ícones**: Font Awesome 6.0
- **Fontes**: Google Fonts (Montserrat)
- **Backend**: PHP
- **Banco de Dados**: MySQL
- **Responsividade**: Mobile-first design

## 📱 Páginas Desenvolvidas

### 1. Página Inicial (index.php)
- Landing page com seleção de perfil (Cliente ou Prestador)
- Design hero com gradientes e animações
- Estatísticas da plataforma
- Seção de benefícios
- Footer completo

### 2. Login do Cliente (login_cliente.php)
- Interface específica para clientes
- Gradiente azul para identificação visual
- Links para cadastro e login de prestador
- Formulário otimizado com validação

### 3. Login do Prestador (login_prestador.php)
- Interface específica para prestadores
- Gradiente verde para diferenciação
- Seção de benefícios para prestadores
- Links para cadastro e login de cliente

### 4. Home do Cliente (home_cliente.php)
- Navbar aprimorada com perfil do usuário
- Hero section com busca inteligente
- Categorias populares
- Filtros avançados
- Grid responsiva de prestadores
- Cards com informações essenciais
- Animações de entrada

### 5. Detalhes do Prestador (detalhes_prestador.php)
- Hero section com foto e informações principais
- Seção "Sobre o Prestador" com estatísticas
- Grid de serviços oferecidos
- Sistema de avaliações
- Sidebar com contato rápido
- Informações de localização e certificações

## 🎯 Melhorias Implementadas

### Design
- Interface moderna com gradientes e glass morphism
- Animações CSS para melhor experiência
- Cards com hover effects
- Sistema de cores consistente
- Tipografia profissional

### Funcionalidade
- Busca em tempo real (estrutura preparada)
- Filtros por categoria e localização
- Sistema de favoritos (estrutura preparada)
- Contato direto via WhatsApp
- Responsividade completa

### Experiência do Usuário
- Navegação intuitiva
- Feedback visual em todas as interações
- Loading states e animações
- Acessibilidade aprimorada
- Performance otimizada

## 📋 Como Usar

### Configuração
1. Configure o banco de dados MySQL
2. Ajuste as credenciais em `connection.php`
3. Certifique-se de que o diretório `uploads/` tenha permissões de escrita
4. Acesse `index.php` para começar

### Fluxo do Cliente
1. Acesse a página inicial
2. Clique em "Sou Cliente"
3. Faça login ou cadastre-se
4. Navegue pela grid de prestadores
5. Clique em um prestador para ver detalhes
6. Entre em contato diretamente

### Fluxo do Prestador
1. Acesse a página inicial
2. Clique em "Sou Prestador"
3. Faça login ou cadastre-se
4. Gerencie seu perfil e serviços

## 🔧 Próximas Melhorias Sugeridas

- Sistema de agendamento online
- Chat em tempo real
- Sistema de pagamento integrado
- Notificações push
- App mobile nativo
- Dashboard analytics para prestadores
- Sistema de geolocalização
- Integração com redes sociais

## 📞 Suporte

Para dúvidas ou suporte técnico, entre em contato através dos canais disponíveis na plataforma.

---

**Search** - Conectando pessoas e serviços com qualidade, segurança e praticidade.

