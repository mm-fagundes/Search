# Correções Aplicadas ao Login com CPF

## Problema Identificado
O aplicativo estava solicitando e-mail e senha para login, mas o esquema do banco de dados fornecido indica que o login deve ser realizado utilizando CPF e senha para a tabela `prestador`.

## Correções Implementadas

### 1. `authService.js` - Lógica de Autenticação
- **Método `signIn`**: Modificado para aceitar `cpf` e `password` como parâmetros.
  - Agora, o serviço busca o `prestador` na tabela `prestador` do Supabase usando o `cpf`.
  - A verificação da `senha` é feita comparando a senha fornecida com a senha armazenada no banco de dados (NOTA: Em um ambiente de produção, a senha deve ser armazenada como um hash e comparada com uma biblioteca de hashing, como `bcrypt`).
  - A criação de sessão é simulada, pois o Supabase Auth nativo é baseado em e-mail/telefone. Para um login exclusivo por CPF, um backend customizado ou uma adaptação mais profunda do Supabase Auth seria necessária.
- **Método `signUp`**: Adaptado para registrar novos prestadores usando `cpf` e `password` diretamente na tabela `prestador`.
  - Inclui verificação para evitar CPFs duplicados.
- **Métodos `signOut`, `getCurrentSession`, `getCurrentUser` e `onAuthStateChange`**: Foram ajustados para refletir a natureza da autenticação customizada, retornando valores simulados ou avisos, já que não dependem mais diretamente do fluxo de autenticação padrão do Supabase.
- **`getErrorMessage`**: As mensagens de erro foram adaptadas para o contexto de CPF/senha.

### 2. `LoginScreen.js` - Interface do Usuário
- **Estado `cpf`**: O `useState` para `email` foi substituído por `cpf`.
- **`handleLoginPress`**: A função de login agora chama `authService.signIn(cpf, senha)`.
- **`InputField`**: O campo de entrada de "Email" foi alterado para "CPF", com `keyboardType="numeric"` para facilitar a entrada.

### 3. `dataService.js` e `HomeScreen.js` - Busca de Dados
- **`dataService.getPrestadorData`**: Modificado para aceitar `prestadorCpf` como identificador e buscar dados na tabela `prestador` usando o campo `cpf`.
- **`HomeScreen.js`**: A lógica de carregamento de dados do usuário (`loadUserData`) foi atualizada para usar o `cpf` obtido da sessão (simulada) para buscar as informações do prestador, total do mês e agendamentos.

## Como Executar o Projeto Atualizado

1.  **Descompacte o arquivo `SearchApp_CPF_Login_Fixed.zip`**.
2.  Navegue até a pasta `SearchApp` no terminal.
3.  **Instale as dependências:**
    ```bash
    npm install
    ```
4.  **Inicie o aplicativo:**
    ```bash
    npm start
    ```
5.  **Teste no seu dispositivo:**
    - Use o aplicativo Expo Go no seu celular.
    - Escaneie o QR code exibido no terminal.

## Observações Importantes

-   **Segurança da Senha**: A implementação atual armazena e compara senhas em texto puro no `authService.js`. **Isso é altamente inseguro e não deve ser usado em produção.** Para um ambiente real, é crucial implementar hashing de senhas (ex: `bcrypt`) tanto no registro quanto na verificação de login.
-   **Autenticação Customizada**: A abordagem de login por CPF é uma autenticação customizada que interage diretamente com a tabela `prestador`. Isso significa que as funcionalidades de autenticação nativas do Supabase (como `supabase.auth.signInWithPassword` e `onAuthStateChange`) não são usadas diretamente para o fluxo de CPF/senha, mas foram mantidas ou adaptadas para evitar erros.
-   **Dados Mock**: Os fallbacks com dados mock ainda estão presentes no `dataService.js` para garantir que o aplicativo funcione mesmo sem uma conexão ativa com o Supabase ou dados reais. Para usar dados reais, certifique-se de que seu Supabase esteja configurado e populado corretamente.

Com essas alterações, o aplicativo agora deve permitir o login usando CPF e senha, conforme o seu esquema de banco de dados.

