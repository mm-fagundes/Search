import { supabase } from './supabase';

export const authService = {
  // Login do prestador com tratamento de erro melhorado
  async signIn(email, password) {
    try {
      // Validação básica
      if (!email || !password) {
        return { success: false, error: 'Email e senha são obrigatórios' };
      }

      const { data, error } = await supabase.auth.signInWithPassword({
        email: email.trim(),
        password: password,
      });

      if (error) {
        console.error('Erro de autenticação:', error);
        return { success: false, error: this.getErrorMessage(error) };
      }

      if (!data.user) {
        return { success: false, error: 'Usuário não encontrado' };
      }

      return { success: true, user: data.user, session: data.session };
    } catch (error) {
      console.error('Erro no login:', error);
      return { success: false, error: 'Erro de conexão. Tente novamente.' };
    }
  },

  // Logout
  async signOut() {
    try {
      const { error } = await supabase.auth.signOut();
      if (error) {
        console.error('Erro no logout:', error);
        return { success: false, error: this.getErrorMessage(error) };
      }
      return { success: true };
    } catch (error) {
      console.error('Erro no logout:', error);
      return { success: false, error: 'Erro ao fazer logout' };
    }
  },

  // Verificar sessão atual
  async getCurrentSession() {
    try {
      const { data: { session }, error } = await supabase.auth.getSession();
      if (error) {
        console.error('Erro ao verificar sessão:', error);
        return { success: false, error: this.getErrorMessage(error) };
      }
      return { success: true, session };
    } catch (error) {
      console.error('Erro ao verificar sessão:', error);
      return { success: false, error: 'Erro ao verificar sessão' };
    }
  },

  // Obter usuário atual
  async getCurrentUser() {
    try {
      const { data: { user }, error } = await supabase.auth.getUser();
      if (error) {
        console.error('Erro ao obter usuário:', error);
        return { success: false, error: this.getErrorMessage(error) };
      }
      return { success: true, user };
    } catch (error) {
      console.error('Erro ao obter usuário:', error);
      return { success: false, error: 'Erro ao obter dados do usuário' };
    }
  },

  // Registrar novo prestador
  async signUp(email, password, userData) {
    try {
      if (!email || !password) {
        return { success: false, error: 'Email e senha são obrigatórios' };
      }

      const { data, error } = await supabase.auth.signUp({
        email: email.trim(),
        password: password,
        options: {
          data: userData
        }
      });

      if (error) {
        console.error('Erro no registro:', error);
        return { success: false, error: this.getErrorMessage(error) };
      }

      return { success: true, user: data.user };
    } catch (error) {
      console.error('Erro no registro:', error);
      return { success: false, error: 'Erro ao criar conta' };
    }
  },

  // Listener para mudanças de autenticação
  onAuthStateChange(callback) {
    try {
      return supabase.auth.onAuthStateChange(callback);
    } catch (error) {
      console.error('Erro ao configurar listener de auth:', error);
      return { data: { subscription: null }, error };
    }
  },

  // Função auxiliar para tratar mensagens de erro
  getErrorMessage(error) {
    if (!error) return 'Erro desconhecido';
    
    switch (error.message) {
      case 'Invalid login credentials':
        return 'Email ou senha incorretos';
      case 'Email not confirmed':
        return 'Email não confirmado';
      case 'Too many requests':
        return 'Muitas tentativas. Tente novamente em alguns minutos';
      case 'User not found':
        return 'Usuário não encontrado';
      default:
        return error.message || 'Erro de autenticação';
    }
  }
};

