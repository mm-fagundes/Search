# SearchApp - Aplicativo para Prestadores de Serviços

## Descrição

O SearchApp é uma plataforma digital desenvolvida em React Native (Expo) que conecta clientes a prestadores de serviços. O aplicativo permite que prestadores gerenciem suas agendas, clientes, serviços e perfis de forma organizada e eficiente.

## Tecnologias Utilizadas

- **React Native** com **Expo** - Framework para desenvolvimento mobile
- **Supabase** - Backend as a Service para autenticação e banco de dados
- **React Navigation** - Navegação entre telas
- **Expo Linear Gradient** - Gradientes visuais

## Funcionalidades Implementadas

### Autenticação
- Login de prestadores com email e senha
- Integração com Supabase Auth
- Verificação de sessão automática

### Telas Principais
1. **Tela de Boas-Vindas** - Primeira tela com opções de entrar ou criar conta
2. **Tela de Login** - Autenticação do prestador
3. **Tela Principal (Home)** - Dashboard com:
   - Perfil do usuário
   - Total de receitas do mês
   - Relatório de receitas recentes
   - Acesso rápido às funcionalidades

### Funcionalidades de Gestão
4. **Programação do Dia** - Lista de agendamentos diários com:
   - Horários dos atendimentos
   - Informações do cliente e serviço
   - Marcação de conclusão de atendimentos

5. **Agendamentos do Mês** - Calendário mensal com:
   - Visualização de agendamentos
   - Lista de compromissos do mês
   - Navegação entre meses

6. **Ajustes de Trabalhos** - Gerenciamento de serviços com:
   - Lista de serviços oferecidos
   - Edição de preços em tempo real
   - Integração com banco de dados

## Estrutura do Banco de Dados

O aplicativo utiliza as seguintes tabelas no Supabase:

- **prestador** - Dados dos prestadores de serviços
- **cliente** - Informações dos clientes
- **servicos** - Catálogo de serviços disponíveis
- **servicos_prestador** - Relacionamento entre prestadores e serviços
- **agendamento** - Agendamentos realizados

## Instalação e Execução

### Pré-requisitos
- Node.js (versão 18 ou superior)
- npm ou yarn
- Expo CLI (opcional, mas recomendado)

### Passos para execução

1. **Instalar dependências:**
   ```bash
   npm install
   ```

2. **Configurar Supabase:**
   - As credenciais já estão configuradas no arquivo `services/supabase.js`
   - Certifique-se de que o banco de dados está configurado corretamente

3. **Executar o aplicativo:**
   ```bash
   npm start
   ```

4. **Visualizar no dispositivo:**
   - Use o aplicativo Expo Go no seu smartphone
   - Escaneie o QR code gerado
   - Ou execute em um emulador Android/iOS

## Estrutura de Arquivos

```
SearchApp/
├── App.js                 # Ponto de entrada principal
├── app.json              # Configurações do Expo
├── package.json          # Dependências e scripts
├── assets/               # Recursos estáticos (imagens, ícones)
├── components/           # Componentes reutilizáveis
│   ├── Button.js
│   └── InputField.js
├── navigation/           # Configuração de navegação
│   └── AppNavigator.js
├── screens/              # Telas do aplicativo
│   ├── WelcomeScreen.js
│   ├── LoginScreen.js
│   ├── HomeScreen.js
│   ├── DailyScheduleScreen.js
│   ├── MonthlyAppointmentsScreen.js
│   └── WorkAdjustmentsScreen.js
└── services/             # Serviços de integração
    ├── supabase.js
    ├── authService.js
    └── dataService.js
```

## Próximos Passos

- Implementar tela de cadastro de novos prestadores
- Adicionar funcionalidade de criação de novos serviços
- Implementar notificações push
- Adicionar sistema de avaliações
- Criar dashboard de analytics
- Implementar chat entre cliente e prestador

## Contribuição

Este projeto foi desenvolvido como parte do curso Técnico em Desenvolvimento de Sistemas. Para contribuições ou melhorias, entre em contato com a equipe de desenvolvimento.

## Equipe

- Enzo Antonio Pintan
- Mateus Miranda Sanches Fagundes
- Kauan Vinicius Silva Calixto

**Orientador:** VIANA, Patrícia Moreno Simões

## Licença

Este projeto é desenvolvido para fins educacionais.

